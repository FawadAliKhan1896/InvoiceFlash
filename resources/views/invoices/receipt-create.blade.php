<x-app-layout>
    <x-slot name="header">Quick Receipt</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card p-6 mb-4">
            <div class="flex items-center gap-3 text-primary-600 mb-1">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                <h2 class="text-lg font-bold">Quick Receipt Mode</h2>
            </div>
            <p class="text-sm text-surface-400">Fast receipt entry for quick transactions. Fill in the basics and generate instantly.</p>
        </div>

        <form method="POST" action="{{ route('receipts.store') }}" x-data="receiptBuilder()">
            @csrf

            <div class="space-y-6">
                <div class="card p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Receipt #</label>
                            <input type="text" name="invoice_number" value="{{ $receiptNumber }}" class="form-input font-mono" readonly>
                        </div>
                        <div>
                            <label class="form-label">Date</label>
                            <input type="date" name="issue_date" value="{{ date('Y-m-d') }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Currency</label>
                            <select name="currency" class="form-select">
                                <option value="PKR" {{ $user->default_currency === 'PKR' ? 'selected' : '' }}>PKR</option>
                                <option value="USD" {{ $user->default_currency === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ $user->default_currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ $user->default_currency === 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Customer Name *</label>
                            <input type="text" name="client_name" class="form-input" placeholder="Walk-in customer" required>
                        </div>
                        <div>
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" value="{{ $user->default_tax_rate }}" class="form-input" min="0" max="100" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card overflow-hidden">
                    <div class="p-6 pb-4">
                        <h2 class="text-base font-bold">Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-surface-50 border-y border-surface-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 uppercase">Item</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-20">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-28">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-28">Total</th>
                                    <th class="px-4 py-3 w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b border-surface-100">
                                        <td class="px-2 py-2">
                                            <input type="text" :name="'items['+index+'][description]'" x-model="item.description" class="w-full px-3 py-2 text-sm border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg" placeholder="Item name" required>
                                        </td>
                                        <td class="px-2 py-2">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculate()" class="w-full px-3 py-2 text-sm text-right font-mono border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg" min="0.01" step="0.01">
                                        </td>
                                        <td class="px-2 py-2">
                                            <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculate()" class="w-full px-3 py-2 text-sm text-right font-mono border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg" min="0" step="0.01">
                                        </td>
                                        <td class="px-4 py-2 text-right font-mono font-semibold" x-text="fmt(item.quantity * item.unit_price)"></td>
                                        <td class="px-2 py-2">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="p-1 text-surface-400 hover:text-danger-500 rounded">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 border-t border-surface-100 flex items-center justify-between">
                        <button type="button" @click="addItem()" class="btn-ghost btn-sm text-primary-600">+ Add Item</button>
                        <div class="text-right">
                            <span class="text-sm text-surface-500">Total: </span>
                            <span class="text-xl font-bold font-mono text-primary-600" x-text="fmt(total)"></span>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <label class="form-label">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="form-textarea" placeholder="Additional notes..."></textarea>
                </div>

                <button type="submit" class="btn-primary w-full btn-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                    Generate Receipt
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function receiptBuilder() {
        return {
            items: [{ description: '', quantity: 1, unit_price: 0 }],
            total: 0,
            init() { this.calculate(); },
            addItem() { this.items.push({ description: '', quantity: 1, unit_price: 0 }); },
            removeItem(i) { if (this.items.length > 1) { this.items.splice(i, 1); this.calculate(); } },
            calculate() { this.total = this.items.reduce((s, i) => s + (parseFloat(i.quantity)||0) * (parseFloat(i.unit_price)||0), 0); },
            fmt(v) { return parseFloat(v||0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}); }
        }
    }
    </script>
    @endpush
</x-app-layout>
