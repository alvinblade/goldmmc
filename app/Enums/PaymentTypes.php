<?php

namespace App\Enums;

use App\Traits\EnumParser;

enum PaymentTypes: string
{
    use EnumParser;

    case CASH = 'cash';
    case TRANSFER = 'transfer';
    case BALANCE = 'balance';

}
