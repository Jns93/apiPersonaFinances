<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_authenticate()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->tokens->first(),
        ])->getJson('/api/v1/auth/me');
        $response->assertStatus(200);
    }

    public function test_user_cannot_authenticate_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('api/v1/sanctum/token', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Credenciais inválidas']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(204);
    }
}
