#!/usr/bin/env php
<?php

declare(strict_types=1);

use DK487\CommissionTask\Controller\CommissionFeeApp;

require __DIR__ . '/vendor/autoload.php';

function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}

error_reporting(E_ALL);
set_error_handler("exception_error_handler");

if ($_SERVER['argc'] != 2) {
    echo 'Usage: php script.php input.csv' . PHP_EOL;
    exit(0);
}

try {
    // NOTE:
    // Do not use *** name in titles, descriptions or the code itself.
    // Thus, I will not use https://.../tasks/api/currency-exchange-rates here
    $app = CommissionFeeApp::initWithExchangeRateLoader('var/sample.json');

    $app->proceedCsvFile($_SERVER['argv'][1]);
} catch (\Exception $exception) {
   echo 'Error: ' . $exception->getMessage() . PHP_EOL;
   exit(1);
}