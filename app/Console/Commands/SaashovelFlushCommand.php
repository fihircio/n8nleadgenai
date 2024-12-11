<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SaashovelFlushCommand extends Command
{
    protected $signature = 'saashovel:flush';

    protected $description = 'Refresh the database and clear all caches';

    public function handle()
    {
        $this->info('Starting the flush process...');

        // Set the command name in $_SERVER['argv']
        $_SERVER['argv'][1] = 'saashovel:flush';

        $this->call('migrate:fresh', ['--seed' => true]);
        $this->info('Database refreshed and seeded.');

        $this->call('optimize:clear');
        $this->info('All caches cleared.');

        $this->info('Flush process completed successfully!');
    }
}
