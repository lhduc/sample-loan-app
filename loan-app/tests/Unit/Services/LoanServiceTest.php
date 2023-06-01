<?php

namespace Tests\Unit\Services;

use App\Constants\LoanStatus;
use App\Constants\UserType;
use App\Exceptions\BadRequestException;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Database\Factories\LoanFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Throwable;

class LoanServiceTest extends TestCase
{
    private LoanService $loanService;
    private User $user;
    private User $otherUser;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loanService = new LoanService();

        $this->user = new User();
        $this->user->id = 1;
        $this->user->type = UserType::USER->value;

        $this->otherUser = new User();
        $this->otherUser->id = 2;
        $this->otherUser->type = UserType::USER->value;
    }

    /**
     * @test
     */
    public function it_cannot_get_other_user_loans()
    {
        $this->actingAs($this->user);

        // Create other user's loans
        LoanFactory::times(1)->create(['user_id' => $this->otherUser->id]);

        // Retrieve the user's loans
        $userLoans = $this->loanService->getLoans();

        // Assert that the retrieved loans belong to the user and are instances of Loan model
        $this->assertEmpty($userLoans);
    }

    /**
     * @test
     */
    public function it_can_get_user_loans()
    {
        $this->actingAs($this->user);

        // Create user's loans
        LoanFactory::times(5)->create(['user_id' => $this->user->id]);

        // Retrieve the user's loans
        $userLoans = $this->loanService->getLoans();

        // Assert that the retrieved loans belong to the user and are instances of Loan model
        $this->assertCount(5, $userLoans);
        $this->assertTrue($userLoans->every(fn ($loan) => $loan->user_id === $this->user->id));
        $this->assertInstanceOf(Loan::class, $userLoans->first());
    }

    /**
     * @test
     */
    public function it_cannot_get_other_user_loan()
    {
        $this->actingAs($this->user);
        $loanId = 1;

        // Create other user's loans
        LoanFactory::times(1)->create(['user_id' => $this->otherUser->id, 'id' => $loanId]);

        // Retrieve the user's loans
        $userLoan = $this->loanService->getLoan($loanId);

        // Assert that the retrieved loans belong to the user and are instances of Loan model
        $this->assertEmpty($userLoan);
    }


    /**
     * @test
     */
    public function it_can_get_user_loan()
    {
        $this->actingAs($this->user);
        $loanId = 1;

        // Create user's loans
        LoanFactory::times(1)->create(['user_id' => $this->user->id, 'id' => $loanId]);

        // Retrieve the user's loans
        $userLoan = $this->loanService->getLoan($loanId);

        // Assert that the retrieved loans belong to the user and are instances of Loan model
        $this->assertTrue($this->user->id === $userLoan->id);
    }

    /**
     * @test
     */
    public function it_can_create_a_user_loan()
    {
        $amount = 1000;
        $terms = 3;
        $loan = $this->loanService->create($this->user->id, 1000, 3);

        $this->assertDatabaseHas('loans', [
            'user_id' => $this->user->id,
            'amount' => $amount,
            'terms' => $terms,
            'status' => LoanStatus::PENDING->value,
        ]);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($this->user->id, $loan->user_id);
        $this->assertEquals($amount, $loan->amount);
        $this->assertEquals($terms, $loan->terms);
        $this->assertEquals(LoanStatus::PENDING->value, $loan->status);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_cannot_approve_a_not_pending_loan()
    {
        $loan = new Loan();
        $loan->status = LoanStatus::APPROVED->value;

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid loan status');

        $this->loanService->approve($loan);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_approve_a_pending_loan()
    {
        $loan = $this->loanService->create($this->user->id, 1000, 3);
        $this->loanService->approve($loan);

        /** @var Collection $installments */
        $installments = $loan->installments();

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals(LoanStatus::APPROVED->value, $loan->status);
        $this->assertEquals($loan->terms, $installments->count());
        $this->assertEquals(round($loan->amount / $loan->terms, 2), $installments->first()->amount);
    }
}
