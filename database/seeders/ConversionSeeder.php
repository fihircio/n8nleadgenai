<?php

namespace Database\Seeders;

use App\Models\Conversion;
use App\Models\User;
use App\Models\Lead;
use App\Models\AiLeadScore;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConversionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $leads = Lead::all();

        if ($users->isEmpty() || $leads->isEmpty()) {
            return;
        }

        // Generate conversions for the last 30 days
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Generate 0-3 conversions per day
            $numConversions = rand(0, 3);
            
            for ($j = 0; $j < $numConversions; $j++) {
                $user = $users->random();
                $lead = $leads->where('user_id', $user->id)->random();
                $aiScore = $lead->aiScore;
                
                $conversionType = $this->getRandomConversionType();
                $status = $this->getRandomStatus();
                $revenue = $this->getRevenueForType($conversionType, $status);
                $dealSize = $revenue * (1 + (rand(-20, 50) / 100)); // ±20-50% variation
                
                Conversion::create([
                    'user_id' => $user->id,
                    'lead_id' => $lead->id,
                    'ai_lead_score_id' => $aiScore ? $aiScore->id : null,
                    'conversion_type' => $conversionType,
                    'status' => $status,
                    'revenue' => $revenue,
                    'deal_size' => $dealSize,
                    'conversion_date' => $date,
                    'notes' => $this->getRandomNotes($conversionType, $status),
                    'metadata' => [
                        'source' => 'manual_entry',
                        'probability' => rand(10, 100),
                    ],
                ]);
            }
        }
    }

    private function getRandomConversionType(): string
    {
        $types = [
            Conversion::TYPE_SALE,
            Conversion::TYPE_MEETING,
            Conversion::TYPE_DEMO,
            Conversion::TYPE_TRIAL,
            Conversion::TYPE_SUBSCRIPTION,
        ];
        
        return $types[array_rand($types)];
    }

    private function getRandomStatus(): string
    {
        $statuses = [
            Conversion::STATUS_COMPLETED => 40, // 40% completed
            Conversion::STATUS_PENDING => 30,   // 30% pending
            Conversion::STATUS_LOST => 20,      // 20% lost
            Conversion::STATUS_DELAYED => 10,   // 10% delayed
        ];
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($statuses as $status => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $status;
            }
        }
        
        return Conversion::STATUS_PENDING;
    }

    private function getRevenueForType(string $type, string $status): float
    {
        if ($status !== Conversion::STATUS_COMPLETED) {
            return 0;
        }

        return match($type) {
            Conversion::TYPE_SALE => rand(1000, 50000) / 100, // $10-$500
            Conversion::TYPE_SUBSCRIPTION => rand(500, 2000) / 100, // $5-$20
            Conversion::TYPE_DEMO => rand(100, 500) / 100, // $1-$5
            Conversion::TYPE_TRIAL => rand(50, 200) / 100, // $0.50-$2
            Conversion::TYPE_MEETING => rand(25, 100) / 100, // $0.25-$1
            default => 0,
        };
    }

    private function getRandomNotes(string $type, string $status): string
    {
        $notes = [
            'Initial contact made',
            'Follow-up scheduled',
            'Proposal sent',
            'Contract signed',
            'Payment received',
            'Implementation started',
            'Customer onboarded',
            'Deal closed successfully',
            'Lost to competitor',
            'Budget constraints',
            'Timeline delayed',
            'Requirements changed',
        ];
        
        return $notes[array_rand($notes)];
    }
} 