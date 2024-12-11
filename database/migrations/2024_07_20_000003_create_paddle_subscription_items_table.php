<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscription_items', function (Blueprint $table) {
            $table->string('product_id')->nullable();
            $table->string('price_id')->nullable();
            $table->string('status')->nullable();
            //$table->unique(['price_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_id',
                'price_id',
                'status',
                'price_id',
            ]);
        });
    }
};
