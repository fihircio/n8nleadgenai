<?php

namespace Database\Seeders;

use App\Models\AiTemplate;
use Illuminate\Database\Seeder;

class AiTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Cold Email Generator',
                'description' => 'Generate personalized cold emails for B2B outreach',
                'prompt' => 'Write a professional cold email to {recipient_name} at {company_name} about {product_service}. The email should be personalized, concise, and include a clear call-to-action. Make it sound natural and avoid being too salesy.',
                'provider' => 'openai',
                'cost_in_coins' => 5,
                'is_active' => true
            ],
            [
                'name' => 'LinkedIn Connection Message',
                'description' => 'Create personalized LinkedIn connection requests',
                'prompt' => 'Write a personalized LinkedIn connection request message for {recipient_name} who works as {job_title} at {company_name}. Mention a specific reason for connecting and keep it under 300 characters.',
                'provider' => 'openai',
                'cost_in_coins' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Follow-up Email Sequence',
                'description' => 'Generate a 3-email follow-up sequence for prospects',
                'prompt' => 'Create a 3-email follow-up sequence for {prospect_name} from {company_name} who showed interest in {product_service}. Include value-add content and gentle reminders. Make each email different and provide specific value.',
                'provider' => 'openai',
                'cost_in_coins' => 8,
                'is_active' => true
            ],
            [
                'name' => 'Voice Message Generator',
                'description' => 'Generate personalized voice messages for lead follow-up',
                'prompt' => 'Hi {recipient_name}, this is {sender_name} calling about {topic}. I wanted to personally reach out because {reason}. I\'d love to schedule a quick call to discuss this further. You can reach me at {phone_number} or reply to this message. Looking forward to connecting!',
                'provider' => 'elevenlabs',
                'cost_in_coins' => 10,
                'is_active' => true
            ],
            [
                'name' => 'Meeting Summary Generator',
                'description' => 'Generate professional meeting summaries and action items',
                'prompt' => 'Create a professional meeting summary for a {meeting_type} meeting with {attendees} about {topic}. Include key points discussed, decisions made, and action items with assigned responsibilities and deadlines.',
                'provider' => 'openai',
                'cost_in_coins' => 4,
                'is_active' => true
            ],
            [
                'name' => 'Proposal Generator',
                'description' => 'Generate customized business proposals',
                'prompt' => 'Create a professional business proposal for {client_name} at {company_name} for {service_description}. Include problem statement, solution, pricing, timeline, and next steps. Make it compelling and professional.',
                'provider' => 'openai',
                'cost_in_coins' => 15,
                'is_active' => true
            ],
            [
                'name' => 'Social Media Post Generator',
                'description' => 'Create engaging social media posts for lead generation',
                'prompt' => 'Write a LinkedIn post about {topic} that provides value to {target_audience}. Include a hook, valuable insights, and a call-to-action. Make it engaging and professional.',
                'provider' => 'openai',
                'cost_in_coins' => 3,
                'is_active' => true
            ],
            [
                'name' => 'Customer Success Story',
                'description' => 'Generate customer success stories and case studies',
                'prompt' => 'Write a customer success story for {client_name} at {company_name} who used {product_service} to achieve {results}. Include challenge, solution, and outcomes. Make it compelling and data-driven.',
                'provider' => 'openai',
                'cost_in_coins' => 12,
                'is_active' => true
            ],
            [
                'name' => 'Sales Pitch Generator',
                'description' => 'Create compelling sales pitches for different audiences',
                'prompt' => 'Create a compelling sales pitch for {product_service} targeting {target_audience}. Focus on the key benefits and value proposition. Make it concise and persuasive.',
                'provider' => 'openai',
                'cost_in_coins' => 6,
                'is_active' => true
            ],
            [
                'name' => 'Objection Handler',
                'description' => 'Generate responses to common sales objections',
                'prompt' => 'Write a professional response to the objection: "{objection}" for {product_service}. Address the concern while highlighting the value and benefits. Be empathetic and solution-focused.',
                'provider' => 'openai',
                'cost_in_coins' => 4,
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            AiTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
} 