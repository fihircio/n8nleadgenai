<?php

namespace App\Livewire\Page\Conversions;

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\Analytics;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class ConversionManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editingConversion = null;
    
    // Form fields
    public $lead_id;
    public $ai_lead_score_id;
    public $conversion_type;
    public $status;
    public $revenue = 0;
    public $deal_size = 0;
    public $conversion_date;
    public $notes;

    protected $rules = [
        'lead_id' => 'required|exists:leads,id',
        'ai_lead_score_id' => 'nullable|exists:ai_lead_scores,id',
        'conversion_type' => 'required|in:sale,meeting,demo,trial,subscription',
        'status' => 'required|in:pending,completed,lost,delayed',
        'revenue' => 'required|numeric|min:0',
        'deal_size' => 'required|numeric|min:0',
        'conversion_date' => 'required|date',
        'notes' => 'nullable|string|max:1000',
    ];

    #[Layout('layouts.app')]
    public function render()
    {
        $conversions = Conversion::where('user_id', Auth::id())
            ->with(['lead', 'aiLeadScore'])
            ->latest('conversion_date')
            ->paginate(10);

        $leads = Lead::where('user_id', Auth::id())
            ->with('aiScore')
            ->get();

        return view('livewire.pages.conversions.conversion-manager', [
            'conversions' => $conversions,
            'leads' => $leads,
            'conversionTypes' => Conversion::getConversionTypes(),
            'statusOptions' => Conversion::getStatusOptions(),
        ]);
    }

    public function createConversion()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editConversion($conversionId)
    {
        $conversion = Conversion::where('user_id', Auth::id())->findOrFail($conversionId);
        
        $this->editingConversion = $conversion;
        $this->lead_id = $conversion->lead_id;
        $this->ai_lead_score_id = $conversion->ai_lead_score_id;
        $this->conversion_type = $conversion->conversion_type;
        $this->status = $conversion->status;
        $this->revenue = $conversion->revenue;
        $this->deal_size = $conversion->deal_size;
        $this->conversion_date = $conversion->conversion_date->format('Y-m-d');
        $this->notes = $conversion->notes;
        
        $this->showForm = true;
    }

    public function saveConversion()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'lead_id' => $this->lead_id,
            'ai_lead_score_id' => $this->ai_lead_score_id,
            'conversion_type' => $this->conversion_type,
            'status' => $this->status,
            'revenue' => $this->revenue,
            'deal_size' => $this->deal_size,
            'conversion_date' => $this->conversion_date,
            'notes' => $this->notes,
        ];

        if ($this->editingConversion) {
            $this->editingConversion->update($data);
            $conversion = $this->editingConversion;
        } else {
            $conversion = Conversion::create($data);
        }

        // Track analytics
        $this->trackConversionAnalytics($conversion);

        $this->showForm = false;
        $this->resetForm();
        
        session()->flash('message', 'Conversion saved successfully!');
    }

    public function deleteConversion($conversionId)
    {
        $conversion = Conversion::where('user_id', Auth::id())->findOrFail($conversionId);
        $conversion->delete();
        
        session()->flash('message', 'Conversion deleted successfully!');
    }

    public function updateStatus($conversionId, $status)
    {
        $conversion = Conversion::where('user_id', Auth::id())->findOrFail($conversionId);
        $conversion->update(['status' => $status]);
        
        // Track analytics for status change
        $this->trackConversionAnalytics($conversion);
        
        session()->flash('message', 'Conversion status updated!');
    }

    private function trackConversionAnalytics($conversion)
    {
        // Get the lead score if available
        $leadScore = $conversion->aiLeadScore ? $conversion->aiLeadScore->score : 0;
        
        Analytics::trackConversion(
            $conversion->user_id,
            $leadScore,
            $conversion->isCompleted(),
            $conversion->revenue
        );
    }

    private function resetForm()
    {
        $this->editingConversion = null;
        $this->lead_id = '';
        $this->ai_lead_score_id = '';
        $this->conversion_type = '';
        $this->status = 'pending';
        $this->revenue = 0;
        $this->deal_size = 0;
        $this->conversion_date = now()->format('Y-m-d');
        $this->notes = '';
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }
} 