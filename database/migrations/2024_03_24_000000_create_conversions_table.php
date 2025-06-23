<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('ai_lead_score_id')->nullable()->constrained()->onDelete('set null');
            $table->string('conversion_type'); // sale, meeting, demo, trial, subscription
            $table->string('status')->default('pending'); // pending, completed, lost, delayed
            $table->decimal('revenue', 10, 2)->default(0);
            $table->decimal('deal_size', 10, 2)->default(0);
            $table->date('conversion_date');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Additional conversion data
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['user_id', 'conversion_date']);
            $table->index(['conversion_type', 'status']);
            $table->index(['ai_lead_score_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
}; 