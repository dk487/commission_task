<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Tests\Util;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\CurrencyExchangeRate;
use DK487\CommissionTask\Util\CurrencyExchangeRates;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeRatesTest extends TestCase
{
    private CurrencyExchangeRates $rates;

    private const EQUAL_FLOAT_DELTA = 0.000001;

    public function setUp(): void
    {
        $this->rates = new CurrencyExchangeRates(
            Currency::EUR,
            new CurrencyExchangeRate(Currency::EUR, Currency::USD, 1.1497),
            new CurrencyExchangeRate(Currency::EUR, Currency::JPY, 129.53),
        );
    }

    /**
     * @dataProvider getSampleExchangeRates
     */
    public function testGetCurrencyExchangeRate(Currency $source, Currency $target, float $expectedRate): void
    {
        $exchangeRate = $this->rates->getCurrencyExchangeRate($source, $target);

        $this->assertEquals($source, $exchangeRate->source);
        $this->assertEquals($target, $exchangeRate->target);
        $this->assertEqualsWithDelta($expectedRate, $exchangeRate->rate, self::EQUAL_FLOAT_DELTA);
    }

    public static function getSampleExchangeRates(): array
    {
        return [
            [Currency::EUR, Currency::EUR, 1],
            [Currency::EUR, Currency::USD, 1.1497],
            [Currency::USD, Currency::EUR, 0.86979211],
            [Currency::USD, Currency::JPY, 112.66417326],
        ];
    }
}
