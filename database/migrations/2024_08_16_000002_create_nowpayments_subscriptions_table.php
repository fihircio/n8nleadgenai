<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nowpayments_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->morphs('billable');
            $table->string('name');
            $table->string('nowpayments_id')->unique();
            $table->string('status');
            $table->string('nowpayments_plan');
            $table->integer('quantity');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nowpayments_subscriptions');
    }
};