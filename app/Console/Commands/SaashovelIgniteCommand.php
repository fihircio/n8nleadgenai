<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SaashovelIgniteCommand extends Command
{
    protected $signature = 'saashovel:ignite';

    protected $description = 'Initialize Saashovel project';

    public function handle()
    {
        $this->info('Starting Saashovel initialization...');

        $commands = [
            ['php', 'artisan', 'key:generate'],
            ['php', 'artisan', 'migrate', '--seed'],
        ];

        foreach ($commands as $command) {
            $process = new Process($command);
            $process->run(function ($type, $buffer) {
                $this->output->write($buffer);
            });

            if (!$process->isSuccessful()) {
                $this->error('Error executing: ' . implode(' ', $command));
                return 1;
            }
        }

        $this->info('🚀 Saashovel has been successfully initialized!');
        $this->info('You can now run:');
        $this->line('   npm run dev');
        $this->line('   php artisan serve');

        return 0;
    }
}
