<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SaashovelProdCommand extends Command
{
    protected $signature = 'saashovel:prod';

    protected $description = 'Deploy application to production mode';

    public function handle()
    {
        try {
            $this->info('Starting production deployment...');

            // Backup .env
            $envPath = base_path('.env');
            if (!File::copy($envPath, $envPath . '.backup')) {
                $this->error('Could not create .env backup');
                return 1;
            }

            // Update .env file
            $this->updateEnvFile();

            $commands = [
                // Core optimizations
                'composer install --no-dev --optimize-autoloader',
                // Clear all caches first
                'php artisan optimize:clear',
                // Caches config and routes
                'php artisan optimize',

                // Filament specific optimizations
                'php artisan filament:optimize', // Caches components and icons

                // Asset building
                'npm install',
                'npm run build',

                // Database and storage
                'php artisan migrate --force',
            ];

            foreach ($commands as $command) {
                $this->info("Executing: {$command}");
                $result = shell_exec($command);
                $this->line($result);
            }

            $this->info('Production deployment completed successfully!');

            $this->info('Post-deployment checklist:');
            $this->line('1. Verify Filament panel access');
            $this->line('2. Check file permissions (especially storage and cache)');
            $this->line('3. Configure web server (Apache/Nginx)');
            $this->line('4. Ensure OPcache is properly configured');
            $this->line('5. Verify User model implements FilamentUser contract');
            $this->line('6. Configure production storage disk in .env');

        } catch (\Exception $e) {
            $this->error('Deployment failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function updateEnvFile()
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $replacements = [
            'APP_ENV=local' => 'APP_ENV=production',
            'APP_DEBUG=true' => 'APP_DEBUG=false',
            //'FILAMENT_FILESYSTEM_DISK=public' => 'FILAMENT_FILESYSTEM_DISK=XXX', // Change XXX to whatever you use for production
        ];

        foreach ($replacements as $search => $replace) {
            $envContent = str_replace($search, $replace, $envContent);
        }

        File::put($envPath, $envContent);
        $this->info('.env file updated for production');
    }
}
