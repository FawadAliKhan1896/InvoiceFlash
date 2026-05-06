<x-app-layout>
    <x-slot name="header">Settings</x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Business Profile -->
                <div class="card p-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary-600 rounded-full"></div>
                        Business Profile
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <!-- Logo -->
                        <div class="sm:col-span-2">
                            <label class="form-label">Business Logo</label>
                            <div class="flex items-center gap-6 mt-2">
                                @if($user->logo_url)
                                    <div class="relative group">
                                        <img src="{{ $user->logo_url }}" alt="Logo" class="h-20 w-20 object-contain rounded-xl border border-slate-200 bg-white p-2">
                                    </div>
                                @else
                                    <div class="h-20 w-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v13.5a2.25 2.25 0 002.25 2.25z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="logo" id="logo" class="hidden" accept="image/*">
                                    <label for="logo" class="btn-secondary btn-sm cursor-pointer inline-flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                        Upload New Logo
                                    </label>
                                    <p class="text-xs text-slate-400 mt-2">PNG or JPG, max 2MB. 200x200px recommended.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $user->business_name) }}" class="form-input" placeholder="Your Business LLC">
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+92 300 1234567">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Physical Address</label>
                            <textarea name="address" rows="2" class="form-textarea" placeholder="Street address">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">City</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-input" placeholder="Lahore">
                        </div>
                        <div>
                            <label class="form-label">Country</label>
                            <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-input">
                        </div>
                    </div>
                </div>

                <!-- Invoicing Preferences -->
                <div class="card p-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary-600 rounded-full"></div>
                        Invoicing Preferences
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="form-label">Default Currency</label>
                            <select name="default_currency" class="form-select">
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" {{ $user->default_currency === $code ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" name="default_tax_rate" value="{{ old('default_tax_rate', $user->default_tax_rate) }}" class="form-input" min="0" max="100" step="0.01">
                        </div>
                        <div>
                            <label class="form-label">Tax Label</label>
                            <input type="text" name="tax_label" value="{{ old('tax_label', $user->tax_label) }}" class="form-input" placeholder="GST, VAT...">
                        </div>
                        <div>
                            <label class="form-label">Invoice Prefix</label>
                            <input type="text" name="invoice_prefix" value="{{ old('invoice_prefix', $user->invoice_prefix) }}" class="form-input font-bold" maxlength="10" placeholder="INV">
                        </div>
                    </div>
                </div>

                <!-- Content Defaults -->
                <div class="card p-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <div class="w-1.5 h-6 bg-primary-600 rounded-full"></div>
                        Default Notes & Terms
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <label class="form-label">Default Invoice Notes</label>
                            <textarea name="default_notes" rows="4" class="form-textarea" placeholder="Thank you for your business!">{{ old('default_notes', $user->default_notes) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">Default Terms & Conditions</label>
                            <textarea name="default_terms" rows="4" class="form-textarea" placeholder="Payment is due within 30 days...">{{ old('default_terms', $user->default_terms) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700">Discard Changes</a>
                    <button type="submit" class="btn-primary btn-lg px-8">
                        Update Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
