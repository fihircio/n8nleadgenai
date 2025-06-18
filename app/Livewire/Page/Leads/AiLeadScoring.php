<?php

namespace App\Livewire\Page\Leads;

use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Services\AiLeadScoringService;
use Illuminate\Support\Facades\Auth;

class AiLeadScoring extends Component
{
    use WithPagination;

    public $selectedLead = null;
    public $showScoreDetails = false;
    public $isScoring = false;
    public $isAdmin = false;

    public function mount()
    {
        $this->isAdmin = Auth::user()->hasRole('admin');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Lead::query();
        
        // If not admin, only show user's leads
        if (!$this->isAdmin) {
            $query->where('user_id', Auth::id());
        }

        $leads = $query->with('aiScore')
            ->latest()
            ->paginate(10);

        return view('livewire.pages.leads.ai-lead-scoring', [
            'leads' => $leads
        ]);
    }

    public function selectLead($leadId)
    {
        $this->selectedLead = Lead::with('aiScore')->find($leadId);
        $this->showScoreDetails = true;
    }

    public function scoreLead()
    {
        if (!$this->selectedLead) {
            return;
        }

        // Check if user has permission to score this lead
        if (!$this->isAdmin && $this->selectedLead->user_id !== Auth::id()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to score this lead.'
            ]);
            return;
        }

        $this->isScoring = true;

        try {
            $scoringService = app(AiLeadScoringService::class);
            $score = $scoringService->scoreLead($this->selectedLead);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Lead scored successfully!'
            ]);

            $this->selectedLead->refresh();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to score lead: ' . $e->getMessage()
            ]);
        } finally {
            $this->isScoring = false;
        }
    }

    public function rescoreLead()
    {
        if (!$this->selectedLead) {
            return;
        }

        // Check if user has permission to rescore this lead
        if (!$this->isAdmin && $this->selectedLead->user_id !== Auth::id()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to rescore this lead.'
            ]);
            return;
        }

        $this->isScoring = true;

        try {
            $scoringService = app(AiLeadScoringService::class);
            $score = $scoringService->scoreLead($this->selectedLead); // Using scoreLead for rescoring

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Lead rescored successfully!'
            ]);

            $this->selectedLead->refresh();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to rescore lead: ' . $e->getMessage()
            ]);
        } finally {
            $this->isScoring = false;
        }
    }
} 