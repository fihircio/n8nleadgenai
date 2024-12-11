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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->morphs('billable');
            $table->string('paddle_id')->unique()->nullable();
            $table->string('status')->nullable();
            $table->timestamp('paused_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'billable',
                'paddle_id',
                'status',
                'paused_at',
            ]);
        });
    }
};
