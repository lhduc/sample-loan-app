<?php

namespace App\Constants;

enum LoanStatus: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case CANCELED = 3;
    case PAID = 4;
}