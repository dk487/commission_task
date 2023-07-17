#!/usr/bin/env php
<?php

declare(strict_types=1);

use DK487\CommissionTask\Controller\CommissionFeeApp;

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['argc'] != 2) {
    echo 'Usage: php script.php input.csv' . PHP_EOL;
    exit(0);
}

// NOTE:
// Do not use *** name in titles, descriptions or the code itself.
// Thus, I will not use https://.../tasks/api/currency-exchange-rates here
$app = CommissionFeeApp::initWithExchangeRateLoader('var/sample.json');

$app->proceedCsvFile($_SERVER['argv'][1]);
