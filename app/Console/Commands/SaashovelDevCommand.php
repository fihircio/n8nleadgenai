<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SaashovelDevCommand extends Command
{
    protected $signature = 'saashovel:dev';

    protected $description = 'Set up application for development mode';

    public function handle()
    {
        try {
            $this->info('Setting up development environment...');

            // Backup .env if exists
            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                File::copy($envPath, $envPath . '.backup');
            }

            // Update .env file
            $this->updateEnvFile();

            $commands = [
                // Clear all caches
                'php artisan optimize:clear',

                // Database
                'php artisan migrate:fresh --seed',

                // Start development servers
                'npm run dev',
            ];

            foreach ($commands as $command) {
                if ($command === 'npm run dev') {
                    $this->info("\nTo start Vite development server, run:");
                    $this->line("npm run dev");
                    continue;
                }

                $this->info("Executing: {$command}");
                $result = shell_exec($command);
                $this->line($result);
            }

            $this->info('Development setup completed successfully!');

            $this->info('Development environment checklist:');
            $this->line('1. Verify database connection');
            $this->line('2. Check storage permissions');
            $this->line('3. Ensure Vite server is running (npm run dev)');
            $this->line('4. Configure IDE for debugging');

        } catch (\Exception $e) {
            $this->error('Setup failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function updateEnvFile()
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $replacements = [
            'APP_ENV=production' => 'APP_ENV=local',
            'APP_DEBUG=false' => 'APP_DEBUG=true',
            //'FILAMENT_FILESYSTEM_DISK=XXX' => 'FILAMENT_FILESYSTEM_DISK=public',
        ];

        foreach ($replacements as $search => $replace) {
            $envContent = str_replace($search, $replace, $envContent);
        }

        File::put($envPath, $envContent);
        $this->info('.env file updated for development');
    }
}
