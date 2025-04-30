<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário e recupera o token
        $user = User::factory()->create();
        $this->token = $user->createToken('test-token')->plainTextToken;
    }

    public function test_stats_endpoints(): void
    {
        $headers = ['Authorization' => "Bearer {$this->token}"];

        // Cria cliente e algumas vendas
        $customer = Customer::factory()->create();
        Sale::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'amount'      => 100.00,
            'sold_at'     => now(),
        ]);

        // daily-sales
        $daily = $this->getJson('/api/stats/daily-sales', $headers);
        $daily->assertStatus(200)
              ->assertJsonStructure([['date', 'total']]);

        // top-customers
        $top = $this->getJson('/api/stats/top-customers', $headers);
        $top->assertStatus(200)
            ->assertJsonStructure([
                'top_volume',
                'top_average',
                'top_frequency'
            ]);
    }
}
