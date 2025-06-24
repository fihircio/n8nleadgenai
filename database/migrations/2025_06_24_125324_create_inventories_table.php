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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreignId('warehouse_id')->references('id')->on('warehouses')->onDelete('restrict');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('reserved_quantity', 10, 2)->default(0);
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('shelf_location')->nullable();
            $table->string('status')->default('available');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Composite unique key
            $table->unique(['product_id', 'warehouse_id', 'batch_number', 'serial_number'], 'inventory_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
