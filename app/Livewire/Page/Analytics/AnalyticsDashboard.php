<?php

namespace App\Livewire\Page\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Analytics Dashboard')]
class AnalyticsDashboard extends Component
{
    public $period = 30;
    public $selectedPeriod = 30;
    public $analytics = [];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        $analyticsService = app(AnalyticsService::class);
        $this->analytics = $analyticsService->getUserAnalytics(Auth::user(), $this->selectedPeriod);
    }

    public function updatedSelectedPeriod()
    {
        $this->loadAnalytics();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.analytics.analytics-dashboard');
    }
} 