<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'business_name', 'phone', 'address', 'city', 'country',
        'logo_path', 'default_currency', 'default_tax_rate', 'tax_label',
        'invoice_prefix', 'next_invoice_number', 'default_notes', 'default_terms', 'plan',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'default_tax_rate' => 'decimal:2',
        ];
    }

    /* ---- Relationships ---- */

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /* ---- Helpers ---- */

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : null;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->business_name ?: $this->name;
    }

    public function isFreePlan(): bool
    {
        return $this->plan === 'free';
    }
}
