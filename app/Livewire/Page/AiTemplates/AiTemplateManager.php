<?php

namespace App\Livewire\Page\AiTemplates;

use App\Models\AiTemplate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AiTemplateManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $name;
    public $description;
    public $prompt;
    public $provider;
    public $cost_in_coins;
    public $is_active = true;
    public $editingTemplate;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required|min:10',
        'prompt' => 'required|min:20',
        'provider' => 'required|in:openai,elevenlabs',
        'cost_in_coins' => 'required|integer|min:1',
        'is_active' => 'boolean'
    ];

    public function render()
    {
        return view('livewire.pages.ai-templates.ai-template-manager', [
            'templates' => AiTemplate::where('is_active', true)
                ->latest()
                ->paginate(10)
        ]);
    }

    public function purchase(AiTemplate $template)
    {
        $user = Auth::user();
        
        if ($user->balance < $template->cost_in_coins) {
            $this->addError('purchase', 'Insufficient coins. Please purchase more coins to use this template.');
            return;
        }

        // Deduct coins using wallet transaction
        $user->wallet->withdraw($template->cost_in_coins, [
            'reason' => "Purchase of AI template: {$template->name}"
        ]);

        // Generate content using the template
        $content = $template->generateContent([
            'user_name' => $user->name,
            'user_email' => $user->email
        ]);

        // Here you would typically save the generated content or handle it as needed
        // For now, we'll just show a success message
        session()->flash('message', 'Template purchased and content generated successfully!');
    }

    protected function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->prompt = '';
        $this->provider = '';
        $this->cost_in_coins = 10;
        $this->is_active = true;
        $this->editingTemplate = null;
    }
} 