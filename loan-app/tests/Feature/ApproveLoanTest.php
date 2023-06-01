<?php

namespace Tests\Feature;

use Database\Factories\LoanFactory;

class ApproveLoanTest extends TestCase
{
    /**
     * @test
     */
    public function it_cannot_approve_loan_by_user(): void
    {
        $loanId = 1;
        $response = $this->post("/api/v1/loans/{$loanId}/approve", [], [
            'Authorization' => 'Bearer ' . $this->getUserToken(),
        ]);

        // Forbidden to approve
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_approve_loan_by_admin(): void
    {
        $loanId = 1;
        LoanFactory::times(1)->create(['user_id' => 2, 'id' => $loanId]);

        $response = $this->post("/api/v1/loans/{$loanId}/approve", [], [
            'Authorization' => 'Bearer ' . $this->getAdminToken(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'user_id',
            'amount',
            'terms',
            'status',
            'installments',
        ]]);
    }
}
