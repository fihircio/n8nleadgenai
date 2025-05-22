<?php
// tests/Feature/CoinApiTest.php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoinApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_coin_balance()
    {
        $user = User::factory()->create();
        $user->addCoins(50);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/coins/balance');
        $response->assertOk()->assertJson(['balance' => 50]);
    }

    public function test_user_can_deposit_and_withdraw_coins()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson('/api/coins/deposit', ['amount' => 20]);
        $this->assertEquals(20, $user->fresh()->getCoinBalance());

        $this->postJson('/api/coins/withdraw', ['amount' => 5]);
        $this->assertEquals(15, $user->fresh()->getCoinBalance());
    }

    public function test_validation_on_coin_endpoints()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson('/api/coins/deposit', ['amount' => -10])
            ->assertStatus(422);
        $this->postJson('/api/coins/withdraw', ['amount' => 0])
            ->assertStatus(422);
    }
}
