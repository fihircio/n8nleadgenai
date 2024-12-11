<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPlan;

class SaashovelEmptyPlansCommand extends Command
{
    protected $signature = 'saashovel:emptyPlans';

    protected $description = 'Delete all records from the subscription_plans table';

    public function handle()
    {
        if ($this->confirm('Are you sure you want to delete all subscription plans? This action cannot be undone.')) {
            $this->call('optimize:clear');
            $this->info('All caches cleared.');
            try {
                $count = SubscriptionPlan::count();
                SubscriptionPlan::truncate();

                $this->info("Successfully deleted {$count} subscription plans.");
            } catch (\Exception $e) {
                $this->error("Failed to delete subscription plans: " . $e->getMessage());
            }
        } else {
            $this->info('Operation cancelled.');
        }
    }
}
