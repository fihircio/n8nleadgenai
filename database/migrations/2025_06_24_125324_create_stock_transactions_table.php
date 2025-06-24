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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('restrict');
            $table->enum('type', ['inbound', 'outbound', 'transfer', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->string('reason')->nullable();
            $table->foreignId('source_warehouse_id')->nullable()->references('id')->on('warehouses')->onDelete('restrict');
            $table->foreignId('destination_warehouse_id')->nullable()->references('id')->on('warehouses')->onDelete('restrict');
            $table->foreignId('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->string('status')->default('completed');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
