<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Page\Leads\AiLeadScoring;

class LivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('page.leads.ai-lead-scoring', AiLeadScoring::class);
    }
} 