<?php

namespace App\Listeners;

use App\Constants\LoanStatus;
use App\Events\InstallmentPaid;
use App\Models\Loan;
use App\Services\InstallmentService;
use Illuminate\Support\Facades\Log;

class SetLoanToPaid
{
    private InstallmentService $installmentService;

    /**
     * Create the event listener.
     */
    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     * Handle the event.
     */
    public function handle(InstallmentPaid $event): void
    {
        $installment = $event->getInstallment();
        if ($this->areAllInstallmentsPaid($installment->loan)) {
            $loan = $installment->loan;
            $loan->status = LoanStatus::PAID->value;
            $loan->save();
            Log::info("Set loan {$loan->id} to paid");
        }
    }

    /**
     * @param Loan $loan
     * @return bool
     */
    private function areAllInstallmentsPaid(Loan $loan): bool
    {
        return $this->installmentService->countPaidInstallments($loan->id) === $loan->terms;
    }
}
