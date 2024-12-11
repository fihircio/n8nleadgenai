<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nowpayments_payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->string('nowpayments_payment_id')->unique();
            $table->string('nowpayments_invoice_id')->nullable();
            $table->string('status');

            $table->string('outcome_amount');
            $table->string('outcome_currency');

            $table->string('price_amount');
            $table->string('price_currency');

            $table->string('pay_amount');
            $table->string('pay_currency');
            $table->string('pay_address')->nullable();

            $table->string('actually_paid')->nullable();
            $table->string('actually_paid_at_fiat')->nullable();

            $table->json('fee')->nullable();

            $table->string('purchase_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nowpayments_payments');
    }
};
