<?php

namespace Tests\Feature;

class LoginTest extends TestCase
{
    /**
     * @test
     */
    public function it_cannot_login_with_missing_params(): void
    {
        $response = $this->post('/api/v1/login');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $response->assertJsonValidationErrors('password');
    }

    /**
     * @test
     */
    public function it_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'lehongduc87@gmail.com',
            'password' => 'wrong-password',
        ]);

        $response->assertBadRequest();
        $response->assertJsonPath('message', 'Invalid credentials');
    }

    /**
     * @test
     */
    public function it_can_login(): void
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'lehongduc87@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['access_token', 'token_type']]);
    }
}
