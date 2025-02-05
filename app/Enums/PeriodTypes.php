<?php

namespace App\Enums;

use App\Traits\EnumParser;

enum PeriodTypes: string
{
    use EnumParser;

    case YEARLY = "yearly";
    case MONTHLY = "monthly";
    case WEEKLY = "weekly";
    case DAILY = "daily";
}
