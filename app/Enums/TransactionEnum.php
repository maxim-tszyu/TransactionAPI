<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum TransactionEnum: string
{
    use EnumTrait;

    case DEPOSIT = 'Deposit';
    case WITHDRAW = 'Withdraw';
    case TRANSFER_IN = 'Transfer_in';
    case TRANSFER_OUT = 'Transfer_out';
}
