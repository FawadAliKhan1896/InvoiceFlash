<x-app-layout>
    <x-slot name="header">Invoices</x-slot>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" x-data="{ submit() { $el.submit() } }" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       @input.debounce.500ms="submit()"
                       class="form-input" placeholder="Invoice # or client...">
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" @change="submit()" class="form-select w-32">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div>
                <label class="form-label">Type</label>
                <select name="type" @change="submit()" class="form-select w-32">
                    <option value="">All</option>
                    <option value="invoice" {{ request('type') === 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="receipt" {{ request('type') === 'receipt' ? 'selected' : '' }}>Receipt</option>
                </select>
            </div>
            <a href="{{ route('invoices.index') }}" class="btn-secondary btn-sm">Clear</a>
        </form>
    </div>

    @if($invoices->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Client</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="font-mono font-bold text-slate-900">
                                <a href="{{ route('invoices.show', $invoice) }}" class="hover:text-primary-600">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td>
                                <p class="font-bold text-slate-800">{{ $invoice->client_name ?: 'Walk-in' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $invoice->issue_date->format('M d, Y') }}</p>
                            </td>
                            <td class="font-mono font-bold text-slate-900">
                                {{ $invoice->getFormattedTotalAttribute() }}
                            </td>
                            <td>
                                <span class="badge-{{ $invoice->status_color }}">
                                    <span class="status-dot-{{ $invoice->status === 'paid' ? 'paid' : ($invoice->status === 'overdue' ? 'overdue' : ($invoice->status === 'sent' ? 'pending' : 'draft')) }}"></span>
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="p-2 text-slate-400 hover:text-primary-600 transition-colors" title="View">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('invoices.pdf', $invoice) }}" class="p-2 text-slate-400 hover:text-primary-600 transition-colors" title="PDF">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $invoices->withQueryString()->links() }}
        </div>
    @else
        <div class="card p-12 text-center">
            <p class="text-slate-400 mb-4 font-medium">No records found matching your criteria.</p>
            <a href="{{ route('invoices.create') }}" class="btn-primary">Create New Invoice</a>
        </div>
    @endif
</x-app-layout>
