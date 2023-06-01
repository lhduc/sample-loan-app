<?php

namespace Tests\Feature;

use App\Models\User;

class ApiTokenTest extends TestCase
{
    /**
     * @test
     */
    public function it_cannot_call_api_without_token(): void
    {
        $response = $this->get('/api/v1/me');

        $response->assertStatus(401);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    /**
     * @test
     */
    public function it_cannot_call_api_with_invalid_token(): void
    {
        $response = $this->get('/api/v1/me', [
            'Authorization' => 'Bearer ' . 'invalid-token'
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_call_api(): void
    {
        $token = User::first()->createToken('api-token')->plainTextToken;

        $response = $this->get('/api/v1/me', [
            'Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);
    }
}
