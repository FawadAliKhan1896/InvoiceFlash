<x-app-layout>
    <x-slot name="header">Add Client</x-slot>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('clients.store') }}">
            @csrf
            <div class="card p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Client Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required autofocus placeholder="Person or company name">
                        @error('name') <p class="text-xs text-danger-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="client@email.com">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+92 300 1234567">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2" class="form-textarea" placeholder="Street address">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="form-input" placeholder="Lahore">
                    </div>
                    <div>
                        <label class="form-label">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'Pakistan') }}" class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="2" class="form-textarea" placeholder="Internal notes about this client...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-surface-200">
                    <button type="submit" class="btn-primary">Save Client</button>
                    <a href="{{ route('clients.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
