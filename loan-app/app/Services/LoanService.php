<?php

namespace App\Services;

use App\Constants\LoanStatus;
use App\Exceptions\BadRequestException;
use App\Models\Loan;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class LoanService
{
    /**
     * @return Collection
     */
    public function getLoans(): Collection
    {
        return Loan::with('installments')->get();
    }

    /**
     * @return Paginator
     */
    public function getLoansWithPagination(): Paginator
    {
        return Loan::with('installments')->simplePaginate(10);
    }

    /**
     * @param int $loanId
     * @return ?Loan
     */
    public function getLoan(int $loanId): ?Loan
    {
        /** @var Loan $loan */
        $loan = Loan::with('installments')->where('id', $loanId)->first();

        return $loan;
    }

    /**
     * @param int $userId
     * @param float $amount
     * @param int $terms
     * @return Loan
     */
    public function create(int $userId, float $amount, int $terms): Loan
    {
        $loan = new Loan();
        $loan->user_id = $userId;
        $loan->amount = $amount;
        $loan->terms = $terms;
        $loan->status = LoanStatus::PENDING->value;
        $loan->save();

        return $loan;
    }

    /**
     * @param Loan $loan
     * @return Loan
     * @throws Throwable
     */
    public function approve(Loan $loan): Loan
    {
        // Only approve when loan status is Pending
        if ($loan->status !== LoanStatus::PENDING->value) {
            throw new BadRequestException('Invalid loan status');
        }

        // Generate installments
        $loan->generateInstallments();

        // Update status
        $loan->status = LoanStatus::APPROVED->value;
        $loan->save();

        return $this->getLoan($loan->id);
    }
}