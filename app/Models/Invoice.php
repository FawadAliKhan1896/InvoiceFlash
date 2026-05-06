<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'client_id', 'invoice_number', 'type', 'status',
        'issue_date', 'due_date', 'currency', 'subtotal',
        'discount_type', 'discount_value', 'discount_amount',
        'tax_rate', 'tax_label', 'tax_amount', 'total',
        'notes', 'terms', 'template', 'brand_color',
        'client_name', 'client_email', 'client_address',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    /* ---- Relationships ---- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /* ---- Scopes ---- */

    public function scopeInvoices(Builder $query): Builder
    {
        return $query->where('type', 'invoice');
    }

    public function scopeReceipts(Builder $query): Builder
    {
        return $query->where('type', 'receipt');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /* ---- Helpers ---- */

    public function getFormattedTotalAttribute(): string
    {
        return $this->currency . ' ' . number_format((float) $this->total, 2);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'success',
            'sent' => 'primary',
            'draft' => 'neutral',
            'overdue' => 'danger',
            'cancelled' => 'neutral',
            default => 'neutral',
        };
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'paid'
            && $this->due_date
            && $this->due_date->isPast();
    }

    /**
     * Recalculate totals from items
     */
    public function recalculate(): void
    {
        $subtotal = $this->items()->sum(\Illuminate\Support\Facades\DB::raw('quantity * unit_price'));

        // Discount
        if ($this->discount_type === 'percentage') {
            $discountAmount = $subtotal * ($this->discount_value / 100);
        } else {
            $discountAmount = $this->discount_value;
        }

        $afterDiscount = $subtotal - $discountAmount;

        // Tax
        $taxAmount = $afterDiscount * ($this->tax_rate / 100);

        $total = $afterDiscount + $taxAmount;

        $this->update([
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total' => round($total, 2),
        ]);
    }

    /**
     * Generate next invoice number for user
     */
    public static function generateNumber(User $user): string
    {
        $number = $user->next_invoice_number;
        $user->increment('next_invoice_number');

        return $user->invoice_prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
