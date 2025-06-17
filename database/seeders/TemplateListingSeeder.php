<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateListing;

class TemplateListingSeeder extends Seeder
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
            [
                'title' => 'AI Voice Message Generator',
                'category' => 'outreach',
                'icon' => 'logos/elevenlabs.png',
                'description' => 'Generate personalized voice messages using ElevenLabs for lead follow-up.',
                'coin_cost' => 3,
                'inputs' => ['lead_name', 'message_template', 'voice_id'],
                'sample_output' => 'Voice message URL'
            ],
            [
                'title' => 'GPT Email Sequence Generator',
                'category' => 'outreach',
                'icon' => 'logos/openai.png',
                'description' => 'Generate personalized email sequences using GPT for cold outreach.',
                'coin_cost' => 2,
                'inputs' => ['lead_profile', 'goal', 'tone'],
                'sample_output' => 'Email sequence JSON'
            ],
            [
                'title' => 'WhatsApp Voice Follow-up',
                'category' => 'outreach',
                'icon' => 'logos/whatsapp.png',
                'description' => 'Send AI-generated voice messages via WhatsApp for lead nurturing.',
                'coin_cost' => 3,
                'inputs' => ['phone', 'message_template', 'voice_id'],
                'sample_output' => 'Message delivery status'
            ]
        ];

        foreach ($templates as $tpl) {
            TemplateListing::updateOrCreate(
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
