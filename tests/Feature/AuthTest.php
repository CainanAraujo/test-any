<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login(): void
    {
        // Registro de usuário
        $response = $this->postJson('/api/register', [
            'name'                  => 'Teste User',
            'email'                 => 'teste@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user'  => ['id', 'email'],
                     'token'
                 ]);

        // Login
        $response = $this->postJson('/api/login', [
            'email'    => 'teste@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user'  => ['id', 'email'],
                     'token'
                 ]);
    }
}
