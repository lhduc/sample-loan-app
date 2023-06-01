<?php

namespace App\Constants;

enum InstallmentStatus: int
{
    case PENDING = 1;
    case PAID = 2;
}