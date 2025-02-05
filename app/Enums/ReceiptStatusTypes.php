<?php

namespace App\Enums;

use App\Traits\EnumParser;

enum ReceiptStatusTypes: string
{
    use EnumParser;

    case PAID = 'paid';
    case UNPAID = 'unpaid';
    case ONE_TIME = 'one_time';
}
