<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Helper;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\CurrencyExchangeRate;
use DK487\CommissionTask\Util\CurrencyExchangeRates;

class CurrencyExchangeRateLoader
{
    public static function loadJson(string $fileName): CurrencyExchangeRates
    {
        $fileContents = file_get_contents($fileName);
        $json = json_decode($fileContents, true);

        $baseCurrency = Currency::from($json['base']);
        $exchangeRates = [];

        foreach ($json['rates'] as $code => $rate) {
            $targetCurrency = Currency::tryFrom($code);

            if ($targetCurrency) {
                $exchangeRates[] = new CurrencyExchangeRate(
                    $baseCurrency,
                    $targetCurrency,
                    $rate,
                );
            }
        }

        return new CurrencyExchangeRates($baseCurrency, ...$exchangeRates);
    }
}
