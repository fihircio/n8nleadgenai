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
            //$table->foreignId('subscription_id')->constrained('nowpayments_subscriptions');
            $table->string('nowpayments_payment_id')->unique();
            $table->string('nowpayments_invoice_id')->nullable();
            $table->string('status');

            // Outcome details
            $table->string('outcome_amount'); 
            $table->string('outcome_currency'); 

            // Price details
            $table->string('price_amount'); 
            $table->string('price_currency');

            // Payment details (actual payment made)
            $table->string('pay_amount'); 
            $table->string('pay_currency');
            $table->string('pay_address')->nullable();

            // Fiat details (using string since they are 0 in the example)
            $table->string('actually_paid')->nullable(); 
            $table->string('actually_paid_at_fiat')->nullable();

            // Fees (store as JSON)
            $table->json('fee')->nullable(); 

            // Other fields from the webhook
            $table->string('purchase_id');

            $table->timestamps(); // Laravel's created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nowpayments_payments'); 
    }
};