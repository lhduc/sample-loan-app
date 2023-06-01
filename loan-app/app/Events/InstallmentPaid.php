<?php

namespace App\Events;

use App\Models\Installment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InstallmentPaid
{
    use Dispatchable, SerializesModels;

    private Installment $installment;

    /**
     * @return Installment
     */
    public function getInstallment(): Installment
    {
        return $this->installment;
    }

    /**
     * Create a new event instance.
     */
    public function __construct(Installment $installment)
    {
        $this->installment = $installment;
    }
}
