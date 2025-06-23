<?php

namespace Database\Seeders;

use App\Models\Analytics;
use App\Models\User;
use App\Models\AiTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $templates = AiTemplate::all();

        if ($users->isEmpty() || $templates->isEmpty()) {
            return;
        }

        // Generate analytics data for the last 30 days
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            foreach ($users as $user) {
                // Lead scoring analytics
                $this->generateLeadScoringData($user, $date);
                
                // Template usage analytics
                $this->generateTemplateUsageData($user, $templates, $date);
                
                // Conversion analytics
                $this->generateConversionData($user, $date);
            }
        }
    }

    private function generateLeadScoringData($user, $date)
    {
        $numLeads = rand(0, 5); // 0-5 leads per day
        
        for ($i = 0; $i < $numLeads; $i++) {
            $score = rand(0, 100);
            
            Analytics::create([
                'user_id' => $user->id,
                'metric_type' => 'lead_scoring',
                'metric_name' => 'score',
                'metric_value' => $score,
                'metadata' => [
                    'lead_id' => rand(1, 100),
                    'lead_email' => 'lead' . rand(1, 1000) . '@example.com',
                    'lead_company' => 'Company ' . rand(1, 50),
                    'lead_title' => 'Manager',
                    'coins_used' => 20,
                ],
                'date' => $date,
            ]);
        }
    }

    private function generateTemplateUsageData($user, $templates, $date)
    {
        $numTemplates = rand(0, 3); // 0-3 templates per day
        
        for ($i = 0; $i < $numTemplates; $i++) {
            $template = $templates->random();
            
            Analytics::create([
                'user_id' => $user->id,
                'metric_type' => 'template_usage',
                'metric_name' => 'purchase',
                'metric_value' => $template->cost_in_coins,
                'metadata' => [
                    'template_id' => $template->id,
                    'success' => true,
                ],
                'date' => $date,
            ]);
        }
    }

    private function generateConversionData($user, $date)
    {
        $numConversions = rand(0, 2); // 0-2 conversions per day
        
        for ($i = 0; $i < $numConversions; $i++) {
            $converted = rand(0, 1);
            $revenue = $converted ? rand(100, 5000) : 0;
            $leadScore = rand(0, 100);
            
            Analytics::create([
                'user_id' => $user->id,
                'metric_type' => 'conversion',
                'metric_name' => 'lead_conversion',
                'metric_value' => $converted,
                'metadata' => [
                    'lead_score' => $leadScore,
                    'revenue' => $revenue,
                ],
                'date' => $date,
            ]);
        }
    }
} 