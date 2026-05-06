<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $receiptNumber = 'RCP-' . str_pad(
            $user->invoices()->receipts()->count() + 1,
            5, '0', STR_PAD_LEFT
        );

        return view('invoices.receipt-create', compact('user', 'receiptNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:50',
            'client_name' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'currency' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
        ]);

        $user = $request->user();

        $invoice = $user->invoices()->create([
            'invoice_number' => $validated['invoice_number'],
            'type' => 'receipt',
            'status' => 'paid',
            'issue_date' => $validated['issue_date'],
            'currency' => $validated['currency'],
            'tax_rate' => $validated['tax_rate'],
            'tax_label' => $user->tax_label,
            'client_name' => $validated['client_name'],
            'notes' => $validated['notes'] ?? null,
            'template' => 'modern',
            'brand_color' => '#6366f1',
        ]);

        foreach ($validated['items'] as $index => $itemData) {
            $invoice->items()->create(array_merge($itemData, [
                'sort_order' => $index,
                'amount' => round($itemData['quantity'] * $itemData['unit_price'], 2),
            ]));
        }

        $invoice->recalculate();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Receipt created successfully!');
    }
}
