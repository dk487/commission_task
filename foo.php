#!/usr/bin/env php
<?php

declare(strict_types=1);

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\CurrencyExchangeRate;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\Operation\OperationType;
use DK487\CommissionTask\Model\Operation\UserType;
use DK487\CommissionTask\Model\UserIdentificator;
use DK487\CommissionTask\Util\CurrencyExchangeRates;

require __DIR__ . '/vendor/autoload.php';

// CSV line:
// 2014-12-31,4,private,withdraw,1200.00,EUR

$csvLine = '2014-12-31,4,private,withdraw,1200.00,EUR';
$csv = str_getcsv($csvLine);

[$date,$uid,$userType,$operationType,$amount,$currency] = $csv;

$foo = new Operation(
    new \DateTimeImmutable($date),
    new UserIdentificator((int) $uid),
    UserType::from($userType),
    OperationType::from($operationType),
    new Money(
        $amount,
        Currency::from($currency)
    )
);

// var_dump($foo);

$bar = new CurrencyExchangeRates(
    Currency::EUR,
    new CurrencyExchangeRate(Currency::EUR, Currency::USD, 1.1497),
    new CurrencyExchangeRate(Currency::EUR, Currency::JPY, 129.53),
);

// var_dump($bar);

$quux = $bar->getCurrencyExchangeRate(Currency::USD, Currency::JPY);

var_dump($quux);