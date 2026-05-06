<x-app-layout>
    <x-slot name="header">Edit Invoice {{ $invoice->invoice_number }}</x-slot>

    <form method="POST" action="{{ route('invoices.update', $invoice) }}" id="invoiceForm"
          x-data="invoiceBuilder()" @submit.prevent="submitForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <!-- Invoice Details -->
                <div class="card p-6">
                    <h2 class="text-base font-bold text-surface-900 mb-4">Invoice Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="form-label">Invoice #</label>
                            <input type="text" class="form-input font-mono bg-surface-50" value="{{ $invoice->invoice_number }}" readonly>
                        </div>
                        <div>
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" x-model="invoice.issue_date" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" x-model="invoice.due_date" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" x-model="invoice.status" class="form-select">
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Client -->
                <div class="card p-6">
                    <h2 class="text-base font-bold text-surface-900 mb-4">Bill To</h2>
                    <input type="hidden" name="client_id" x-model="invoice.client_id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Client Name *</label>
                            <input type="text" name="client_name" x-model="invoice.client_name" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="client_email" x-model="invoice.client_email" class="form-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Address</label>
                            <textarea name="client_address" x-model="invoice.client_address" rows="2" class="form-textarea"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card overflow-hidden">
                    <div class="p-6 pb-4">
                        <h2 class="text-base font-bold text-surface-900">Items</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-surface-50 border-y border-surface-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-24">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-32">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase w-32">Amount</th>
                                    <th class="px-4 py-3 w-12"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b border-surface-100">
                                        <td class="px-4 py-2 text-surface-400 font-mono text-xs" x-text="index + 1"></td>
                                        <td class="px-2 py-2">
                                            <input type="text" :name="'items['+index+'][description]'" x-model="item.description"
                                                   class="w-full px-3 py-2 text-sm border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg transition-all">
                                        </td>
                                        <td class="px-2 py-2">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculate()"
                                                   class="w-full px-3 py-2 text-sm text-right font-mono border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg transition-all" min="0.01" step="0.01">
                                        </td>
                                        <td class="px-2 py-2">
                                            <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculate()"
                                                   class="w-full px-3 py-2 text-sm text-right font-mono border-0 bg-transparent focus:bg-white focus:ring-2 focus:ring-primary-500/20 rounded-lg transition-all" min="0" step="0.01">
                                        </td>
                                        <td class="px-4 py-2 text-right font-mono font-semibold" x-text="formatCurrency(item.quantity * item.unit_price)"></td>
                                        <td class="px-2 py-2">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                    class="p-1.5 text-surface-400 hover:text-danger-500 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 border-t border-surface-100">
                        <button type="button" @click="addItem()" class="btn-ghost btn-sm text-primary-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Add Item
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Notes</label>
                            <textarea name="notes" x-model="invoice.notes" rows="3" class="form-textarea"></textarea>
                        </div>
                        <div>
                            <label class="form-label">Terms</label>
                            <textarea name="terms" x-model="invoice.terms" rows="3" class="form-textarea"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="xl:col-span-1 space-y-6">
                <div class="card p-6 sticky top-24">
                    <h2 class="text-base font-bold text-surface-900 mb-5">Summary</h2>
                    <div class="mb-4">
                        <label class="form-label">Currency</label>
                        <select name="currency" x-model="invoice.currency" class="form-select">
                            <option value="PKR">PKR</option><option value="USD">USD</option><option value="EUR">EUR</option>
                            <option value="GBP">GBP</option><option value="AED">AED</option><option value="SAR">SAR</option>
                        </select>
                    </div>
                    <div class="space-y-3 py-4 border-t border-surface-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Subtotal</span>
                            <span class="font-mono" x-text="invoice.currency + ' ' + formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-surface-500">Discount</span>
                            <input type="number" name="discount_value" x-model.number="invoice.discount_value" @input="calculate()" class="w-16 px-2 py-1 text-xs text-right font-mono border border-surface-200 rounded-lg" min="0">
                            <select name="discount_type" x-model="invoice.discount_type" @change="calculate()" class="px-2 py-1 text-xs border border-surface-200 rounded-lg">
                                <option value="percentage">%</option><option value="fixed">Fixed</option>
                            </select>
                            <span class="font-mono text-sm text-danger-500 ml-auto" x-text="'-' + formatCurrency(discountAmount)"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" name="tax_label" x-model="invoice.tax_label" class="w-16 px-1 py-0 text-xs border-0 border-b border-dashed border-surface-300 bg-transparent">
                            <input type="number" name="tax_rate" x-model.number="invoice.tax_rate" @input="calculate()" class="w-16 px-2 py-1 text-xs text-right font-mono border border-surface-200 rounded-lg" min="0" max="100">
                            <span class="text-xs text-surface-400">%</span>
                            <span class="font-mono text-sm ml-auto" x-text="'+' + formatCurrency(taxAmount)"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t-2 border-surface-900">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-2xl font-bold font-mono text-primary-600" x-text="invoice.currency + ' ' + formatCurrency(total)"></span>
                    </div>
                    <input type="hidden" name="type" x-model="invoice.type">
                    <input type="hidden" name="template" x-model="invoice.template">
                    <input type="hidden" name="brand_color" x-model="invoice.brand_color">

                    <div class="mt-6">
                        <label class="form-label">Template</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" @click="invoice.template = 'modern'" :class="invoice.template === 'modern' ? 'ring-2 ring-primary-500 border-primary-500' : 'border-surface-200'" class="p-3 border rounded-xl text-center transition-all">
                                <div class="w-full h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-md mb-1.5"></div>
                                <span class="text-[10px] font-semibold text-surface-600">Modern</span>
                            </button>
                            <button type="button" @click="invoice.template = 'minimal'" :class="invoice.template === 'minimal' ? 'ring-2 ring-primary-500 border-primary-500' : 'border-surface-200'" class="p-3 border rounded-xl text-center transition-all">
                                <div class="w-full h-8 bg-surface-100 border border-surface-200 rounded-md mb-1.5"></div>
                                <span class="text-[10px] font-semibold text-surface-600">Minimal</span>
                            </button>
                            <button type="button" @click="invoice.template = 'corporate'" :class="invoice.template === 'corporate' ? 'ring-2 ring-primary-500 border-primary-500' : 'border-surface-200'" class="p-3 border rounded-xl text-center transition-all">
                                <div class="w-full h-8 bg-surface-800 rounded-md mb-1.5"></div>
                                <span class="text-[10px] font-semibold text-surface-600">Corporate</span>
                            </button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Brand Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="invoice.brand_color" class="w-10 h-10 rounded-lg border border-surface-200 cursor-pointer">
                            <input type="text" x-model="invoice.brand_color" class="form-input font-mono text-sm flex-1" maxlength="7">
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <button type="submit" class="btn-primary w-full btn-lg">Save Changes</button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn-secondary w-full text-center">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
    function invoiceBuilder() {
        return {
            invoice: {
                issue_date: '{{ $invoice->issue_date->format("Y-m-d") }}',
                due_date: '{{ $invoice->due_date?->format("Y-m-d") ?? "" }}',
                status: '{{ $invoice->status }}',
                currency: '{{ $invoice->currency }}',
                client_id: '{{ $invoice->client_id ?? "" }}',
                client_name: @json($invoice->client_name ?? ''),
                client_email: @json($invoice->client_email ?? ''),
                client_address: @json($invoice->client_address ?? ''),
                discount_type: '{{ $invoice->discount_type }}',
                discount_value: {{ $invoice->discount_value ?? 0 }},
                tax_rate: {{ $invoice->tax_rate ?? 0 }},
                tax_label: @json($invoice->tax_label ?? 'Tax'),
                notes: @json($invoice->notes ?? ''),
                terms: @json($invoice->terms ?? ''),
                template: '{{ $invoice->template }}',
                brand_color: '{{ $invoice->brand_color }}',
                type: '{{ $invoice->type }}',
            },
            items: @json($invoice->items->map(fn($i) => ['description' => $i->description, 'quantity' => (float)$i->quantity, 'unit_price' => (float)$i->unit_price])),
            subtotal: 0, discountAmount: 0, taxAmount: 0, total: 0,
            init() { this.calculate(); },
            addItem() { this.items.push({ description: '', quantity: 1, unit_price: 0 }); },
            removeItem(index) { if (this.items.length > 1) { this.items.splice(index, 1); this.calculate(); } },
            calculate() {
                this.subtotal = this.items.reduce((s, i) => s + (parseFloat(i.quantity)||0) * (parseFloat(i.unit_price)||0), 0);
                this.discountAmount = this.invoice.discount_type === 'percentage' ? this.subtotal * ((parseFloat(this.invoice.discount_value)||0)/100) : (parseFloat(this.invoice.discount_value)||0);
                const afterDiscount = this.subtotal - this.discountAmount;
                this.taxAmount = afterDiscount * ((parseFloat(this.invoice.tax_rate)||0)/100);
                this.total = afterDiscount + this.taxAmount;
            },
            formatCurrency(v) { return parseFloat(v||0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2}); },
            submitForm() { document.getElementById('invoiceForm').submit(); }
        }
    }
    </script>
    @endpush
</x-app-layout>
