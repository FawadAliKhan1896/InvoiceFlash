<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $currencies = [
            'PKR' => 'Pakistani Rupee (PKR)',
            'USD' => 'US Dollar (USD)',
            'EUR' => 'Euro (EUR)',
            'GBP' => 'British Pound (GBP)',
            'AED' => 'UAE Dirham (AED)',
            'SAR' => 'Saudi Riyal (SAR)',
            'INR' => 'Indian Rupee (INR)',
            'CAD' => 'Canadian Dollar (CAD)',
            'AUD' => 'Australian Dollar (AUD)',
        ];

        return view('settings.index', compact('user', 'currencies'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'default_currency' => 'required|string|max:10',
            'default_tax_rate' => 'required|numeric|min:0|max:100',
            'tax_label' => 'required|string|max:50',
            'invoice_prefix' => 'required|string|max:10',
            'default_notes' => 'nullable|string|max:2000',
            'default_terms' => 'nullable|string|max:2000',
            'logo' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($user->logo_path) {
                Storage::disk('public')->delete($user->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        unset($validated['logo']);
        $user->update($validated);

        return back()->with('success', 'Settings saved successfully!');
    }

    public function removeLogo(Request $request)
    {
        $user = $request->user();

        if ($user->logo_path) {
            Storage::disk('public')->delete($user->logo_path);
            $user->update(['logo_path' => null]);
        }

        return back()->with('success', 'Logo removed.');
    }
}
