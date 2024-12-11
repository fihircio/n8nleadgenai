<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nowpayments_customers', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->string('nowpayments_id')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nowpayments_customers');
    }
};