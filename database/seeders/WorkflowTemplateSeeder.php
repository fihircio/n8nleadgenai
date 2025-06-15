<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkflowTemplate;

class WorkflowTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'title' => 'LinkedIn Event Attendee Scraper',
                'category' => 'sourcing',
                'icon' => 'logos/linkedin.png',
                'description' => 'Scrape attendees from LinkedIn events for lead generation.',
                'coin_cost' => 3,
                'inputs' => ['keyword', 'location'],
                'sample_output' => 'Table of attendee names, titles, and companies.'
            ],
            [
                'title' => 'Reddit Post Monitor',
                'category' => 'sourcing',
                'icon' => 'logos/reddit.png',
                'description' => 'Monitor Reddit posts for specific keywords and get notified.',
                'coin_cost' => 2,
                'inputs' => ['subreddit', 'keyword'],
                'sample_output' => 'List of matching Reddit posts.'
            ],
            [
                'title' => 'Company Info via Clearbit',
                'category' => 'enrichment',
                'icon' => 'logos/clearbit.png',
                'description' => 'Enrich company data using Clearbit API.',
                'coin_cost' => 2,
                'inputs' => ['domain'],
                'sample_output' => 'Company profile with industry, size, and location.'
            ],
            [
                'title' => 'Cold Email via SendGrid',
                'category' => 'outreach',
                'icon' => 'logos/sendgrid.png',
                'description' => 'Send cold emails to leads using SendGrid.',
                'coin_cost' => 1,
                'inputs' => ['email', 'template'],
                'sample_output' => 'Email delivery status.'
            ],
            [
                'title' => 'Auto CRM Entry',
                'category' => 'automation',
                'icon' => 'logos/crm.png',
                'description' => 'Automatically add new leads to your CRM.',
                'coin_cost' => 2,
                'inputs' => ['lead_name', 'email', 'company'],
                'sample_output' => 'CRM entry confirmation.'
            ],
            [
                'title' => 'Weekly Lead Summary via Email',
                'category' => 'reporting',
                'icon' => 'logos/email.png',
                'description' => 'Receive a weekly summary of your leads via email.',
                'coin_cost' => 1,
                'inputs' => ['email'],
                'sample_output' => 'Summary email preview.'
            ],
        ];

        foreach ($templates as $tpl) {
            WorkflowTemplate::updateOrCreate(
                ['title' => $tpl['title']],
                [
                    'category' => $tpl['category'],
                    'icon' => $tpl['icon'],
                    'description' => $tpl['description'],
                    'coin_cost' => $tpl['coin_cost'],
                    'inputs' => $tpl['inputs'],
                    'sample_output' => $tpl['sample_output'],
                ]
            );
        }
    }
}
