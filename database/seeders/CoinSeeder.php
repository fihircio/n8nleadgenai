<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CoinSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Add coins if user has 0 balance
            if ($user->getCoinBalance() == 0) {
                $coinsToAdd = $user->hasRole('admin') ? 500 : 300;
                $user->addCoins($coinsToAdd, [
                    'reason' => 'Initial testing coins',
                    'seeder' => 'CoinSeeder'
                ]);
                
                $this->command->info("Added {$coinsToAdd} coins to {$user->email}");
            }
        }
    }
} 