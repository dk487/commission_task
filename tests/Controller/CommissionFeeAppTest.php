<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Tests\Util;

use DK487\CommissionTask\Controller\CommissionFeeApp;
use DK487\CommissionTask\Service\CommissionFeeCalculator;
use DK487\CommissionTask\Service\CurrencyConvertor;
use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\CurrencyExchangeRate;
use DK487\CommissionTask\Util\CurrencyExchangeRates;
use PHPUnit\Framework\TestCase;

class CommissionFeeAppTest extends TestCase
{
    private CommissionFeeApp $app;

    public function setUp(): void
    {
        $this->app = new CommissionFeeApp(
            new CommissionFeeCalculator(
                new CurrencyConvertor(
                    new CurrencyExchangeRates(
                        Currency::EUR,
                        new CurrencyExchangeRate(Currency::EUR, Currency::USD, 1.1497),
                        new CurrencyExchangeRate(Currency::EUR, Currency::JPY, 129.53),
                    ),
                ),
            ),
        );
    }


    public function testGetCurrencyExchangeRate(): void
    {
        $input = [
            '2014-12-31,4,private,withdraw,1200.00,EUR',
            '2015-01-01,4,private,withdraw,1000.00,EUR',
            '2016-01-05,4,private,withdraw,1000.00,EUR',
            '2016-01-05,1,private,deposit,200.00,EUR',
            '2016-01-06,2,business,withdraw,300.00,EUR',
            '2016-01-06,1,private,withdraw,30000,JPY',
            '2016-01-07,1,private,withdraw,1000.00,EUR',
            '2016-01-07,1,private,withdraw,100.00,USD',
            '2016-01-10,1,private,withdraw,100.00,EUR',
            '2016-01-10,2,business,deposit,10000.00,EUR',
            '2016-01-10,3,private,withdraw,1000.00,EUR',
            '2016-02-15,1,private,withdraw,300.00,EUR',
            '2016-02-19,5,private,withdraw,3000000,JPY',
        ];

        $expectedOutput = <<<OUTPUT
0.60
3.00
0.00
0.06
1.50
0
0.70
0.30
0.30
3.00
0.00
0.00
8612
OUTPUT;

        $this->expectOutputString($expectedOutput);
        $this->app->proceedCsv($input);
    }
}
