<?php

namespace App\Livewire\Page\Marketplace;

use App\Models\WorkflowTemplate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Workflow Marketplace')]
class WorkflowMarketplace extends Component
{
    public $categories = [
        'sourcing' => 'Sourcing',
        'enrichment' => 'Enrichment',
        'outreach' => 'Outreach',
        'automation' => 'Automation',
        'reporting' => 'Reporting',
    ];

    public $activeCategory = 'sourcing';

    public $showModal = false;
    public $selectedTemplate = null;

    public function selectCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function previewTemplate($id)
    {
        $this->selectedTemplate = WorkflowTemplate::find($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTemplate = null;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $filteredTemplates = WorkflowTemplate::where('category', $this->activeCategory)->get();
        return view('livewire.pages.marketplace.workflow-marketplace', [
            'filteredTemplates' => $filteredTemplates,
        ]);
    }
}
