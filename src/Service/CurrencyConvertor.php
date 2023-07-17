<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Service;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Util\BcMath;
use DK487\CommissionTask\Util\CurrencyExchangeRates;

readonly class CurrencyConvertor
{
    public function __construct(
        public CurrencyExchangeRates $currencyExchangeRates,
    ) {
    }

    public function convert(
        Money $sourceMoney,
        Currency $targetCurrency,
    ): Money {
        $exchangeRate = $this->currencyExchangeRates->getCurrencyExchangeRate(
            $sourceMoney->currency,
            $targetCurrency,
        );
        
        $targetAmount = BcMath::multiply(
            $sourceMoney->amount,
            $exchangeRate->rate,
            $targetCurrency->getDecimalPoints(),
        );

        return new Money($targetAmount, $targetCurrency);
    }
}