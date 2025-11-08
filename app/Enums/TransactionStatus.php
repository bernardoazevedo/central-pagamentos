<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case TO_PAY = 'TO_PAY';
    case PAID = 'PAID';
    case CHARGED_BACK = 'CHARGED_BACK';
}
