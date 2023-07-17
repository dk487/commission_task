#!/usr/bin/env php
<?php

declare(strict_types=1);

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Helper\CurrencyExchangeRateLoader;
use DK487\CommissionTask\Helper\CsvOperationsParser;
use DK487\CommissionTask\Service\CurrencyConvertor;

require __DIR__ . '/vendor/autoload.php';

$csvLine = '2014-12-31,4,private,withdraw,1200.00,EUR';
$foo = CsvOperationsParser::parseLine($csvLine);

var_dump($foo);

$bar = new CurrencyConvertor(
    CurrencyExchangeRateLoader::loadJson('var/sample.json')
);

$x = new Money(100, Currency::USD);
echo $x . ' equals to ' . $bar->convert($x, Currency::EUR) . PHP_EOL;
