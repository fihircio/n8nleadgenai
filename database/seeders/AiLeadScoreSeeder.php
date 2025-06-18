<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\AiLeadScore;
use Illuminate\Database\Seeder;

class AiLeadScoreSeeder extends Seeder
{
    public function run(): void
    {
        $leads = Lead::all();

        foreach ($leads as $lead) {
            // Generate a random score between 0-100
            $score = rand(0, 100);
            
            // Determine category based on score
            $category = $this->getCategory($score);
            
            // Generate scoring factors
            $scoringFactors = $this->generateScoringFactors($score);
            
            // Generate enrichment data
            $enrichmentData = $this->generateEnrichmentData($lead);

            AiLeadScore::create([
                'lead_id' => $lead->id,
                'score' => $score,
                'scoring_factors' => $scoringFactors,
                'enrichment_data' => $enrichmentData,
                'status' => 'completed'
            ]);
        }
    }

    private function getCategory($score): string
    {
        if ($score >= 80) return 'Hot';
        if ($score >= 50) return 'Warm';
        return 'Cold';
    }

    private function generateScoringFactors($score): array
    {
        $factors = [];
        
        if ($score >= 80) {
            $factors = [
                'company_size' => 'Large Enterprise',
                'decision_maker' => true,
                'budget_available' => true,
                'urgency' => 'High',
                'technology_adoption' => 'Advanced'
            ];
        } elseif ($score >= 50) {
            $factors = [
                'company_size' => 'Medium Business',
                'decision_maker' => true,
                'budget_available' => 'Moderate',
                'urgency' => 'Medium',
                'technology_adoption' => 'Moderate'
            ];
        } else {
            $factors = [
                'company_size' => 'Small Business',
                'decision_maker' => false,
                'budget_available' => false,
                'urgency' => 'Low',
                'technology_adoption' => 'Basic'
            ];
        }

        return $factors;
    }

    private function generateEnrichmentData($lead): array
    {
        return [
            'linkedin_profile' => 'https://linkedin.com/in/' . strtolower(str_replace(' ', '', $lead->name)),
            'company_website' => 'https://' . strtolower(str_replace(' ', '', $lead->company)) . '.com',
            'industry' => $this->getIndustry($lead->company),
            'location' => $this->getLocation(),
            'company_revenue' => $this->getRevenue($lead->company)
        ];
    }

    private function getIndustry($company): string
    {
        $industries = ['Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing', 'Retail'];
        return $industries[array_rand($industries)];
    }

    private function getLocation(): string
    {
        $locations = ['New York, NY', 'San Francisco, CA', 'Austin, TX', 'Seattle, WA', 'Boston, MA', 'Chicago, IL'];
        return $locations[array_rand($locations)];
    }

    private function getRevenue($company): string
    {
        $revenues = ['$1M-$10M', '$10M-$50M', '$50M-$100M', '$100M-$500M', '$500M+'];
        return $revenues[array_rand($revenues)];
    }
} 