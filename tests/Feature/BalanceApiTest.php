<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function deposit_successful()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/deposit', [
            'user_id' => $user->id,
            'amount' => 500,
            'comment' => 'Пополнение'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status', 'message', 'transaction'
            ]);
    }

    #[Test]
    public function withdraw_not_enough_balance()
    {
        $user = User::factory()->create();

        $this->postJson('/api/deposit', [
            'user_id' => $user->id,
            'amount' => 50,
            'comment' => 'Начальный депозит'
        ]);

        $response = $this->postJson('/api/withdraw', [
            'user_id' => $user->id,
            'amount' => 100
        ]);

        $response->assertStatus(409)
            ->assertJson(['message' => 'Not enough balance.']);
    }

    #[Test]
    public function transfer_between_users()
    {
        [$u1, $u2] = User::factory()->count(2)->create();

        $this->postJson('/api/deposit', [
            'user_id' => $u1->id,
            'amount' => 200
        ]);

        $response = $this->postJson('/api/transfer', [
            'from_user_id' => $u1->id,
            'to_user_id' => $u2->id,
            'amount' => 100
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'transaction',
                'related_transaction'
            ]);
    }

    #[Test]
    public function balance_returns_correct_value()
    {
        $user = User::factory()->create();

        $this->postJson('/api/deposit', [
            'user_id' => $user->id,
            'amount' => 300
        ]);

        $response = $this->getJson("/api/balance/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'user_id' => $user->id,
                'balance' => 300
            ]);
    }
}
