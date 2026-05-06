<x-app-layout>
    <x-slot name="header">Create Invoice</x-slot>

    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm"
          x-data="invoiceBuilder()" @submit.prevent="submitForm">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Left: Form -->
            <div class="xl:col-span-3 space-y-8">
                <!-- Basic Info -->
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1 h-6 bg-primary-600 rounded-full"></div>
                        Invoice Details
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="form-label">Invoice Number</label>
                            <input type="text" name="invoice_number" x-model="invoice.invoice_number" class="form-input bg-slate-50 font-bold" readonly>
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
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Client -->
                <div class="card p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1 h-6 bg-primary-600 rounded-full"></div>
                        Client Information
                    </h2>
                    <div x-data="clientSearch()" class="relative mb-6">
                        <label class="form-label">Search Saved Client</label>
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="search()" @focus="showDropdown = true"
                                   placeholder="Type client name..." class="form-input pl-10">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        </div>
                        <!-- Dropdown -->
                        <div x-show="showDropdown && results.length > 0" @click.away="showDropdown = false"
                             class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden">
                            <template x-for="client in results" :key="client.id">
                                <button type="button" @click="selectClient(client)" class="w-full px-4 py-3 text-left hover:bg-slate-50 border-b border-slate-100 last:border-0 transition-colors">
                                    <p class="font-bold text-slate-900" x-text="client.name"></p>
                                    <p class="text-xs text-slate-500" x-text="client.email || ''"></p>
                                </button>
                            </template>
                        </div>
                    </div>

                    <input type="hidden" name="client_id" x-model="invoice.client_id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Client Name <span class="text-red-500">*</span></label>
                            <input type="text" name="client_name" x-model="invoice.client_name" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Client Email</label>
                            <input type="email" name="client_email" x-model="invoice.client_email" class="form-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Billing Address</label>
                            <textarea name="client_address" x-model="invoice.client_address" rows="2" class="form-textarea"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <div class="w-1 h-6 bg-primary-600 rounded-full"></div>
                            Items & Services
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-y border-slate-200">
                                    <th class="px-6 py-3 w-12">#</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3 text-right w-32">Quantity</th>
                                    <th class="px-6 py-3 text-right w-40">Unit Price</th>
                                    <th class="px-6 py-3 text-right w-40">Amount</th>
                                    <th class="px-6 py-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-3 text-slate-400 font-bold" x-text="index + 1"></td>
                                        <td class="px-2 py-3">
                                            <input type="text" :name="'items['+index+'][description]'" x-model="item.description"
                                                   class="form-input border-0 bg-transparent focus:bg-white" placeholder="Description..." required>
                                        </td>
                                        <td class="px-2 py-3 text-right">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculate()"
                                                   class="form-input border-0 bg-transparent focus:bg-white text-right font-bold" min="0.01" step="0.01">
                                        </td>
                                        <td class="px-2 py-3 text-right">
                                            <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculate()"
                                                   class="form-input border-0 bg-transparent focus:bg-white text-right font-bold" min="0" step="0.01">
                                        </td>
                                        <td class="px-6 py-3 text-right font-bold text-slate-900" x-text="formatCurrency(item.quantity * item.unit_price)"></td>
                                        <td class="px-6 py-3">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-slate-400 hover:text-red-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        <button type="button" @click="addItem()" class="btn-secondary btn-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Add New Line
                        </button>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="card p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" x-model="invoice.notes" rows="3" class="form-textarea" placeholder="Payment instructions, bank info, etc."></textarea>
                    </div>
                    <div>
                        <label class="form-label">Terms & Conditions</label>
                        <textarea name="terms" x-model="invoice.terms" rows="3" class="form-textarea" placeholder="Late fees, refund policy..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="xl:col-span-1 space-y-6">
                <div class="card p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Summary</h2>
                    
                    <div class="mb-6">
                        <label class="form-label">Currency</label>
                        <select name="currency" x-model="invoice.currency" class="form-select">
                            <option value="PKR">PKR - Rupee</option>
                            <option value="USD">USD - Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - Pound</option>
                        </select>
                    </div>

                    <div class="space-y-4 py-6 border-y border-slate-100">
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="text-slate-900" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-slate-500 w-20">Discount</span>
                            <div class="flex flex-1 gap-1">
                                <input type="number" name="discount_value" x-model.number="invoice.discount_value" @input="calculate()" class="form-input py-1 px-2 text-right">
                                <select name="discount_type" x-model="invoice.discount_type" @change="calculate()" class="form-select py-1 px-1 w-16">
                                    <option value="percentage">%</option>
                                    <option value="fixed">$</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-slate-500 w-20">
                                <input type="text" name="tax_label" x-model="invoice.tax_label" class="w-full bg-transparent border-0 border-b border-slate-200 focus:border-primary-500 focus:ring-0 p-0 text-sm">
                            </span>
                            <div class="flex flex-1 items-center gap-2">
                                <input type="number" name="tax_rate" x-model.number="invoice.tax_rate" @input="calculate()" class="form-input py-1 px-2 text-right">
                                <span class="text-sm text-slate-400">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="py-6">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Amount</span>
                            <span class="text-xs font-bold text-slate-400" x-text="invoice.currency"></span>
                        </div>
                        <p class="text-3xl font-black text-primary-600 tracking-tight" x-text="formatCurrency(total)"></p>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" class="btn-primary w-full btn-lg">Save Invoice</button>
                        <a href="{{ route('invoices.index') }}" class="btn-secondary w-full">Cancel</a>
                    </div>

                    <div class="mt-8 pt-8 border-t border-slate-100">
                        <label class="form-label">Template</label>
                        <select name="template" x-model="invoice.template" class="form-select">
                            <option value="modern">Modern Professional</option>
                            <option value="minimal">Minimal Elegant</option>
                            <option value="corporate">Corporate Standard</option>
                        </select>
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
                invoice_number: '{{ $invoiceNumber }}',
                issue_date: '{{ date('Y-m-d') }}',
                due_date: '{{ date('Y-m-d', strtotime('+30 days')) }}',
                status: 'draft',
                currency: '{{ $user->default_currency }}',
                client_id: '{{ request('client_id', '') }}',
                client_name: '{{ $clients->find(request('client_id'))?->name ?? '' }}',
                client_email: '{{ $clients->find(request('client_id'))?->email ?? '' }}',
                client_address: '{{ $clients->find(request('client_id'))?->full_address ?? '' }}',
                discount_type: 'percentage',
                discount_value: 0,
                tax_rate: {{ $user->default_tax_rate }},
                tax_label: '{{ $user->tax_label }}',
                notes: @json($user->default_notes ?? ''),
                terms: @json($user->default_terms ?? ''),
                template: 'modern',
                brand_color: '#FF6B00',
            },
            items: [
                { description: '', quantity: 1, unit_price: 0 }
            ],
            subtotal: 0,
            discountAmount: 0,
            taxAmount: 0,
            total: 0,

            init() {
                this.calculate();
            },

            addItem() {
                this.items.push({ description: '', quantity: 1, unit_price: 0 });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                    this.calculate();
                }
            },

            calculate() {
                this.subtotal = this.items.reduce((sum, item) => {
                    return sum + (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                }, 0);

                if (this.invoice.discount_type === 'percentage') {
                    this.discountAmount = this.subtotal * ((parseFloat(this.invoice.discount_value) || 0) / 100);
                } else {
                    this.discountAmount = parseFloat(this.invoice.discount_value) || 0;
                }

                const afterDiscount = this.subtotal - this.discountAmount;
                this.taxAmount = afterDiscount * ((parseFloat(this.invoice.tax_rate) || 0) / 100);
                this.total = afterDiscount + this.taxAmount;
            },

            formatCurrency(value) {
                return parseFloat(value || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            submitForm() {
                document.getElementById('invoiceForm').submit();
            }
        }
    }

    function clientSearch() {
        return {
            searchQuery: '',
            results: [],
            showDropdown: false,

            async search() {
                if (this.searchQuery.length < 2) {
                    this.results = [];
                    return;
                }
                try {
                    const response = await fetch(`/api/clients/search?q=${encodeURIComponent(this.searchQuery)}`);
                    this.results = await response.json();
                    this.showDropdown = true;
                } catch (e) {
                    this.results = [];
                }
            },

            selectClient(client) {
                this.searchQuery = client.name;
                this.showDropdown = false;

                const builder = Alpine.$data(document.getElementById('invoiceForm'));
                if (builder) {
                    builder.invoice.client_id = client.id;
                    builder.invoice.client_name = client.name;
                    builder.invoice.client_email = client.email || '';
                    builder.invoice.client_address = [client.address, client.city, client.country].filter(Boolean).join(', ');
                }
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
