<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model\Operation;

enum OperationType: string
{
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';
}
