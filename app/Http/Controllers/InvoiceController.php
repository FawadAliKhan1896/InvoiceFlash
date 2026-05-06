<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->invoices()->with('client')->latest();

        // Filters
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }
        if ($from = $request->input('from')) {
            $query->where('issue_date', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->where('issue_date', '<=', $to);
        }

        $invoices = $query->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $clients = $user->clients()->orderBy('name')->get();
        $invoiceNumber = Invoice::generateNumber($user);
        $templates = ['modern', 'minimal', 'corporate'];

        return view('invoices.create', compact('clients', 'invoiceNumber', 'templates', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'invoice_number' => 'required|string|max:50',
            'type' => 'required|in:invoice,receipt',
            'status' => 'required|in:draft,sent,paid',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'required|string|max:10',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_label' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'terms' => 'nullable|string|max:2000',
            'template' => 'required|in:modern,minimal,corporate',
            'brand_color' => 'nullable|string|max:7',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $user = $request->user();

        // Snapshot client info
        if ($validated['client_id']) {
            $client = $user->clients()->find($validated['client_id']);
            if ($client) {
                $validated['client_name'] = $client->name;
                $validated['client_email'] = $client->email;
                $validated['client_address'] = $client->full_address;
            }
        }

        $invoice = $user->invoices()->create(array_merge($validated, [
            'tax_label' => $validated['tax_label'] ?? $user->tax_label,
            'brand_color' => $validated['brand_color'] ?? '#6366f1',
        ]));

        // Create items
        foreach ($validated['items'] as $index => $itemData) {
            $invoice->items()->create(array_merge($itemData, [
                'sort_order' => $index,
                'amount' => round($itemData['quantity'] * $itemData['unit_price'], 2),
            ]));
        }

        // Recalculate totals
        $invoice->recalculate();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->load('items', 'client', 'payments');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->load('items', 'client');

        $user = request()->user();
        $clients = $user->clients()->orderBy('name')->get();
        $templates = ['modern', 'minimal', 'corporate'];

        return view('invoices.edit', compact('invoice', 'clients', 'templates', 'user'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'type' => 'required|in:invoice,receipt',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'required|string|max:10',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_label' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'terms' => 'nullable|string|max:2000',
            'template' => 'required|in:modern,minimal,corporate',
            'brand_color' => 'nullable|string|max:7',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_address' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Snapshot client info
        if ($validated['client_id']) {
            $client = $request->user()->clients()->find($validated['client_id']);
            if ($client) {
                $validated['client_name'] = $client->name;
                $validated['client_email'] = $client->email;
                $validated['client_address'] = $client->full_address;
            }
        }

        $invoice->update($validated);

        // Recreate items
        $invoice->items()->delete();
        foreach ($validated['items'] as $index => $itemData) {
            $invoice->items()->create(array_merge($itemData, [
                'sort_order' => $index,
                'amount' => round($itemData['quantity'] * $itemData['unit_price'], 2),
            ]));
        }

        $invoice->recalculate();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Generate and download PDF
     */
    public function pdf(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);
        $invoice->load('items', 'user');

        $template = in_array($invoice->template, ['modern', 'minimal', 'corporate'])
            ? $invoice->template
            : 'modern';

        $pdf = Pdf::loadView("pdf.{$template}", compact('invoice'));
        $pdf->setPaper('a4');

        $filename = $invoice->invoice_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Duplicate an invoice
     */
    public function duplicate(Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $user = request()->user();
        $newInvoice = $invoice->replicate([
            'invoice_number', 'status', 'created_at', 'updated_at', 'deleted_at'
        ]);
        $newInvoice->invoice_number = Invoice::generateNumber($user);
        $newInvoice->status = 'draft';
        $newInvoice->issue_date = now();
        $newInvoice->due_date = now()->addDays(30);
        $newInvoice->save();

        // Duplicate items
        foreach ($invoice->items as $item) {
            $newItem = $item->replicate();
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        return redirect()->route('invoices.edit', $newInvoice)
            ->with('success', 'Invoice duplicated! Edit and save when ready.');
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $this->authorizeInvoice($invoice);

        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
        ]);

        $invoice->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status updated to ' . ucfirst($request->input('status')));
    }

    private function authorizeInvoice(Invoice $invoice): void
    {
        if ($invoice->user_id !== request()->user()->id) {
            abort(403);
        }
    }
}
