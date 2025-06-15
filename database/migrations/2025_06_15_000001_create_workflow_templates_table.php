<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('coin_cost')->default(1);
            $table->json('inputs')->nullable();
            $table->text('sample_output')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_templates');
    }
};
