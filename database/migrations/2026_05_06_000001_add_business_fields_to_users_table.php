<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('country', 100)->default('Pakistan')->after('city');
            $table->string('logo_path')->nullable()->after('country');
            $table->string('default_currency', 10)->default('PKR')->after('logo_path');
            $table->decimal('default_tax_rate', 5, 2)->default(0)->after('default_currency');
            $table->string('tax_label', 50)->default('Tax')->after('default_tax_rate');
            $table->string('invoice_prefix', 10)->default('INV')->after('tax_label');
            $table->integer('next_invoice_number')->default(1)->after('invoice_prefix');
            $table->text('default_notes')->nullable()->after('next_invoice_number');
            $table->text('default_terms')->nullable()->after('default_notes');
            $table->string('plan', 20)->default('free')->after('default_terms');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'business_name', 'phone', 'address', 'city', 'country',
                'logo_path', 'default_currency', 'default_tax_rate', 'tax_label',
                'invoice_prefix', 'next_invoice_number', 'default_notes', 'default_terms', 'plan'
            ]);
        });
    }
};
