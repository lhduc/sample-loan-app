<?php

namespace Tests\Feature;

class CreateLoanTest extends TestCase
{
    private array $headers;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getUserToken(),
        ];
    }

    /**
     * @test
     */
    public function it_can_create_loan(): void
    {
        $data = [
            'amount' => 10000,
            'terms' => 3
        ];
        $response = $this->post('/api/v1/loans', $data, $this->headers);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'user_id',
            'amount',
            'terms',
            'status',
            'created_at',
            'updated_at',
        ]]);
        $response->assertJsonFragment($data);
    }
}
