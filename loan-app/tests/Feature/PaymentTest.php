<?php

namespace Tests\Feature;

use App\Constants\InstallmentStatus;
use App\Constants\LoanStatus;
use App\Models\Installment;
use App\Models\Loan;
use App\Services\LoanService;
use Throwable;

class PaymentTest extends TestCase
{
    private array $headers;
    private Loan $loan;

    /**
     * @return void
     * @throws Throwable
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getUserToken(),
        ];

        $loanService = new LoanService();
        $this->loan = $loanService->create($this->user->id, 10000, 3);
        $this->loan = $loanService->approve($this->loan);
    }

    /**
     * @test
     */
    public function it_cannot_pay_for_installment_with_insufficient_amount(): void
    {
        /** @var Installment $installment */
        $installment = $this->loan->installments()->first();
        $data = [
            'installment_id' => $installment->id,
            'amount' => 1,
        ];
        $response = $this->post('/api/v1/payments', $data, $this->headers);

        $response->assertBadRequest();
        $response->assertJsonPath('message', 'Insufficient amount');
    }

    /**
     * @test
     */
    public function it_cannot_pay_form_paid_installment(): void
    {
        /** @var Installment $installment */
        $installment = $this->loan->installments()->first();
        $installment->status = InstallmentStatus::PAID->value;
        $installment->save();

        $data = [
            'installment_id' => $installment->id,
            'amount' => $installment->amount,
        ];

        $response = $this->post('/api/v1/payments', $data, $this->headers);

        $response->assertBadRequest();
        $response->assertJsonPath('message', 'This installment has paid');
    }

    /**
     * @test
     */
    public function it_can_pay_for_installment(): void
    {
        /** @var Installment $installment */
        $installment = $this->loan->installments()->first();
        $data = [
            'installment_id' => $installment->id,
            'amount' => $installment->amount,
        ];

        $response = $this->post('/api/v1/payments', $data, $this->headers);

        $response->assertOk();
        $response->assertJsonFragment(['paid_amount' => $installment->amount, 'status' => InstallmentStatus::PAID->value]);
    }

    /**
     * @test
     */
    public function it_can_update_loan_status_after_paid_all_installments(): void
    {
        $installments = $this->loan->installments()->get()->all();
        foreach ($installments as $installment) {
            $data = [
                'installment_id' => $installment->id,
                'amount' => $installment->amount,
            ];
            $response = $this->post('/api/v1/payments', $data, $this->headers);
            $response->assertOk();
            $response->assertJsonPath('data.status', InstallmentStatus::PAID->value);
        }

        // Assert loan status is paid
        $loan = Loan::find($this->loan->id);
        $this->assertEquals(LoanStatus::PAID->value, $loan->status);
    }
}
