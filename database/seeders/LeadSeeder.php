<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $leads = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@techcompany.com',
                'phone' => '+1-555-0123',
                'company' => 'Tech Solutions Inc.',
                'title' => 'CTO',
                'source' => 'LinkedIn',
                'status' => 'new',
                'notes' => 'Interested in AI solutions'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@startup.io',
                'phone' => '+1-555-0124',
                'company' => 'Innovative Startups',
                'title' => 'CEO',
                'source' => 'Website',
                'status' => 'new',
                'notes' => 'Looking for automation tools'
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'mchen@enterprise.com',
                'phone' => '+1-555-0125',
                'company' => 'Enterprise Solutions',
                'title' => 'VP of Operations',
                'source' => 'Referral',
                'status' => 'new',
                'notes' => 'Enterprise client, high potential'
            ],
            [
                'name' => 'Emily Brown',
                'email' => 'emily.b@smallbiz.com',
                'phone' => '+1-555-0126',
                'company' => 'Small Business Solutions',
                'title' => 'Owner',
                'source' => 'Email Campaign',
                'status' => 'new',
                'notes' => 'Small business owner, budget conscious'
            ],
            [
                'name' => 'David Wilson',
                'email' => 'dwilson@corporate.com',
                'phone' => '+1-555-0127',
                'company' => 'Corporate Enterprises',
                'title' => 'IT Director',
                'source' => 'Trade Show',
                'status' => 'new',
                'notes' => 'Large enterprise, multiple departments'
            ]
        ];

        foreach ($leads as $lead) {
            Lead::create(array_merge($lead, ['user_id' => $user->id]));
        }
    }
} 