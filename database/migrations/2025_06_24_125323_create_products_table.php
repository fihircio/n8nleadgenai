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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->string('unit')->default('piece'); // piece, kg, liter, etc.
            $table->boolean('track_serial')->default(false);
            $table->boolean('track_expiry')->default(false);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->nullable();
            $table->boolean('active')->default(true);
            $table->json('metadata')->nullable(); // For additional custom fields
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
