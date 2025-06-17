<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_templates', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Then drop the columns
            $table->dropColumn(['user_id', 'type', 'template_data', 'variables']);
            
            // Add new columns
            $table->integer('cost_in_coins')->default(10)->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('ai_templates', function (Blueprint $table) {
            // Drop new columns first
            $table->dropColumn(['cost_in_coins']);
            
            // Add back the original columns
            $table->unsignedBigInteger('user_id')->after('id');
            $table->string('type')->after('name');
            $table->json('template_data')->after('provider');
            $table->json('variables')->after('template_data');
            
            // Re-add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}; 