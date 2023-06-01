<?php

namespace App\Services;

use App\Constants\InstallmentStatus;
use App\Models\Installment;

class InstallmentService
{
    /**
     * @param int $id
     * @return ?Installment
     */
    public function getInstallment(int $id): ?Installment
    {
        return Installment::where('id', $id)->first();
    }

    /**
     * @param int $id
     * @param float $amount
     * @return Installment
     */
    public function setToPaid(int $id, float $amount): Installment
    {
        $installment = $this->getInstallment($id);
        $installment->status = InstallmentStatus::PAID->value;
        $installment->paid_date = new \DateTime();
        $installment->paid_amount = $amount;
        $installment->save();

        return $installment;
    }

    /**
     * @param int $loanId
     * @return int
     */
    public function countPaidInstallments(int $loanId): int
    {
        return Installment::where('loan_id', $loanId)->where('status', InstallmentStatus::PAID->value)->count();
    }
}
