<?php
// tests/Unit/UserCoinTest.php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCoinTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_and_subtract_coins()
    {
        $user = User::factory()->create();
        $user->addCoins(100);
        $this->assertEquals(100, $user->getCoinBalance());
        $user->subtractCoins(40);
        $this->assertEquals(60, $user->getCoinBalance());
    }

    public function test_cannot_subtract_more_than_balance()
    {
        $user = User::factory()->create();
        $user->addCoins(10);
        $this->expectException(\Bavix\Wallet\Exceptions\InsufficientFunds::class);
        $user->subtractCoins(20);
    }
}
