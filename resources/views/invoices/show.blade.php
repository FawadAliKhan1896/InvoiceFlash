<x-app-layout>
    <x-slot name="header">{{ $invoice->invoice_number }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <span class="badge-{{ $invoice->status_color }} text-sm">
                    <span class="status-dot-{{ $invoice->status === 'paid' ? 'paid' : ($invoice->status === 'overdue' ? 'overdue' : ($invoice->status === 'sent' ? 'pending' : 'draft')) }}"></span>
                    {{ ucfirst($invoice->status) }}
                </span>
                <span class="badge-{{ $invoice->type === 'invoice' ? 'primary' : 'neutral' }}">{{ ucfirst($invoice->type) }}</span>
            </div>
            <div class="flex items-center gap-2">
                <!-- Status Quick Actions -->
                @if($invoice->status !== 'paid')
                    <form method="POST" action="{{ route('invoices.status', $invoice) }}" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn-success btn-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Mark Paid
                        </button>
                    </form>
                @endif
                @if($invoice->status === 'draft')
                    <form method="POST" action="{{ route('invoices.status', $invoice) }}" class="inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="sent">
                        <button type="submit" class="btn-secondary btn-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            Mark Sent
                        </button>
                    </form>
                @endif
                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Download PDF
                </a>
                <a href="{{ route('invoices.edit', $invoice) }}" class="btn-secondary btn-sm">Edit</a>
                <form method="POST" action="{{ route('invoices.duplicate', $invoice) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-ghost btn-sm">Duplicate</button>
                </form>
            </div>
        </div>

        <!-- Invoice Preview -->
        <div class="card p-8 lg:p-12">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between gap-6 mb-10">
                <div>
                    @if($invoice->user->logo_url)
                        <img src="{{ $invoice->user->logo_url }}" alt="Logo" class="h-12 mb-3">
                    @endif
                    <h2 class="text-xl font-bold text-surface-900">{{ $invoice->user->display_name }}</h2>
                    @if($invoice->user->address)
                        <p class="text-sm text-surface-500 mt-1">{{ $invoice->user->address }}</p>
                    @endif
                    @if($invoice->user->phone)
                        <p class="text-sm text-surface-500">{{ $invoice->user->phone }}</p>
                    @endif
                    <p class="text-sm text-surface-500">{{ $invoice->user->email }}</p>
                </div>
                <div class="text-right">
                    <h1 class="text-3xl font-bold uppercase tracking-wider" style="color: {{ $invoice->brand_color }}">
                        {{ $invoice->type === 'receipt' ? 'Receipt' : 'Invoice' }}
                    </h1>
                    <p class="font-mono text-lg font-semibold text-surface-700 mt-2"># {{ $invoice->invoice_number }}</p>
                    <div class="text-sm text-surface-500 mt-2 space-y-0.5">
                        <p>Issued: {{ $invoice->issue_date->format('M d, Y') }}</p>
                        @if($invoice->due_date)
                            <p>Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bill To -->
            @if($invoice->client_name)
                <div class="mb-8 p-4 bg-surface-50 rounded-xl">
                    <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider mb-1">Bill To</p>
                    <p class="font-semibold text-surface-900">{{ $invoice->client_name }}</p>
                    @if($invoice->client_email)
                        <p class="text-sm text-surface-500">{{ $invoice->client_email }}</p>
                    @endif
                    @if($invoice->client_address)
                        <p class="text-sm text-surface-500">{{ $invoice->client_address }}</p>
                    @endif
                </div>
            @endif

            <!-- Items Table -->
            <table class="w-full text-sm mb-8">
                <thead>
                    <tr class="border-b-2" style="border-color: {{ $invoice->brand_color }}">
                        <th class="pb-3 text-left font-semibold text-surface-600">#</th>
                        <th class="pb-3 text-left font-semibold text-surface-600">Description</th>
                        <th class="pb-3 text-right font-semibold text-surface-600">Qty</th>
                        <th class="pb-3 text-right font-semibold text-surface-600">Price</th>
                        <th class="pb-3 text-right font-semibold text-surface-600">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr class="border-b border-surface-100">
                            <td class="py-3 text-surface-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-3 text-surface-800">{{ $item->description }}</td>
                            <td class="py-3 text-right font-mono text-surface-600">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                            <td class="py-3 text-right font-mono text-surface-600">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 text-right font-mono font-semibold text-surface-800">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-72 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-surface-500">Subtotal</span>
                        <span class="font-mono">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Discount {{ $invoice->discount_type === 'percentage' ? '('.$invoice->discount_value.'%)' : '' }}</span>
                            <span class="font-mono text-danger-500">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($invoice->tax_amount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">{{ $invoice->tax_label }} ({{ $invoice->tax_rate }}%)</span>
                            <span class="font-mono">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t-2 border-surface-900">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-lg font-bold font-mono" style="color: {{ $invoice->brand_color }}">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Terms -->
            @if($invoice->notes || $invoice->terms)
                <div class="mt-10 pt-6 border-t border-surface-200 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if($invoice->notes)
                        <div>
                            <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider mb-1">Notes</p>
                            <p class="text-sm text-surface-600">{{ $invoice->notes }}</p>
                        </div>
                    @endif
                    @if($invoice->terms)
                        <div>
                            <p class="text-xs font-semibold text-surface-400 uppercase tracking-wider mb-1">Terms & Conditions</p>
                            <p class="text-sm text-surface-600">{{ $invoice->terms }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Delete -->
        <div class="mt-6 flex justify-end">
            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                  onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-ghost btn-sm text-danger-500 hover:text-danger-600 hover:bg-danger-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    Delete Invoice
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
