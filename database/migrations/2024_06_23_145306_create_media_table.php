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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name');
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->string('collection_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->json('custom_properties')->nullable();
            $table->json('generated_conversions')->nullable();
            $table->json('responsive_images')->nullable();
            $table->json('manipulations')->nullable();
            $table->foreignId('model_id');
            $table->string('model_type');
            $table->uuid('uuid');
            $table->integer('order_column')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
