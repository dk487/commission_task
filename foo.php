#!/usr/bin/env php
<?php

declare(strict_types=1);

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\Operation\OperationType;
use DK487\CommissionTask\Model\Operation\UserType;

require __DIR__ . '/vendor/autoload.php';

// CSV line:
// 2014-12-31,4,private,withdraw,1200.00,EUR

$foo = new Operation(
    new \DateTimeImmutable('2014-12-31 UTC'),
    4,
    UserType::Private,
    OperationType::Withdraw,
    new Money(
        '1200.00',
        Currency::EUR
    )
);

var_dump($foo);
