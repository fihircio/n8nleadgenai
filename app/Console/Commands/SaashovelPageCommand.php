<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SaashovelPageCommand extends Command
{
    protected $signature = 'saashovel:page {name}';

    protected $description = 'Create a new Livewire page component with its own directory';

    public function handle()
    {
        $name = $this->argument('name');
        $kebabName = Str::kebab($name);

        $componentPath = $this->createComponentFile($name, $kebabName);
        $viewPath = $this->createViewFile($name, $kebabName);

        $this->info("Livewire page component '{$name}' created successfully.");
        $this->displayCreatedPaths($componentPath, $viewPath);
    }

    protected function createComponentFile($name, $kebabName)
    {
        $componentPath = app_path("Livewire/Page/{$name}/{$name}.php");
        $componentContent = $this->getComponentContent($name, $kebabName);

        File::ensureDirectoryExists(dirname($componentPath));
        File::put($componentPath, $componentContent);

        return $componentPath;
    }

    protected function createViewFile($name, $kebabName)
    {
        $viewPath = resource_path("views/livewire/pages/{$kebabName}/{$kebabName}.blade.php");
        $viewContent = $this->getViewContent();

        File::ensureDirectoryExists(dirname($viewPath));
        File::put($viewPath, $viewContent);

        return $viewPath;
    }

    protected function displayCreatedPaths($componentPath, $viewPath)
    {
        $this->info('Created files:');
        $this->line("Component: " . $componentPath);
        $this->line("View: " . $viewPath);
    }

    protected function getComponentContent($name, $kebabName)
    {
        return <<<PHP
<?php

namespace App\Livewire\Page\\{$name};

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('{$name}')]
class {$name} extends Component
{
    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.pages.{$kebabName}.{$kebabName}');
    }
}
PHP;
    }

    protected function getViewContent()
    {
        return <<<'BLADE'
<div>
    {{-- Page content here --}}
</div>
BLADE;
    }
}
