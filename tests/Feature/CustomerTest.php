<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
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

    public function test_customer_crud_flow(): void
    {
        $headers = ['Authorization' => "Bearer {$this->token}"];

        // CREATE
        $payload = ['name' => 'Alice', 'email' => 'alice@example.com'];
        $create = $this->postJson('/api/customers', $payload, $headers);
        $create->assertStatus(201)
               ->assertJsonFragment($payload);

        $id = $create->json('id');

        // INDEX + filtro por name
        $index = $this->getJson("/api/customers?name=Alice", $headers);
        $index->assertStatus(200)
              ->assertJsonFragment(['name' => 'Alice']);

        // SHOW
        $show = $this->getJson("/api/customers/{$id}", $headers);
        $show->assertStatus(200)
             ->assertJsonFragment(['email' => 'alice@example.com']);

        // UPDATE
        $update = $this->putJson("/api/customers/{$id}", ['name' => 'Alice B.'], $headers);
        $update->assertStatus(200)
               ->assertJsonFragment(['name' => 'Alice B.']);

        // DELETE
        $delete = $this->deleteJson("/api/customers/{$id}", [], $headers);
        $delete->assertStatus(204);
    }
}
