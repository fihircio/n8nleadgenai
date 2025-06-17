<?php

namespace App\Livewire\Page\Marketplace;

use App\Models\TemplateListing;
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
    public $filteredTemplates = [];

    public function selectCategory($category)
    {
        $this->activeCategory = $category;
        $this->filteredTemplates = TemplateListing::where('category', $this->activeCategory)->get();
    }

    public function previewTemplate($id)
    {
        $this->selectedTemplate = TemplateListing::find($id);
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
        $filteredTemplates = TemplateListing::where('category', $this->activeCategory)->get();
        return view('livewire.pages.marketplace.workflow-marketplace', [
            'filteredTemplates' => $filteredTemplates,
        ]);
    }
}
