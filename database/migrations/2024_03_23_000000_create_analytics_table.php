<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('metric_type'); // lead_scoring, template_usage, conversion, etc.
            $table->string('metric_name'); // score, purchase, lead_conversion, etc.
            $table->decimal('metric_value', 10, 2); // The actual metric value
            $table->json('metadata')->nullable(); // Additional data like lead info, template details, etc.
            $table->date('date'); // Date for time-based analytics
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['user_id', 'metric_type', 'date']);
            $table->index(['metric_type', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
}; 