<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Util;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\CurrencyExchangeRate;

readonly class CurrencyExchangeRates
{
    private array $exchangeRates;

    private const ERROR_NOT_FOUND = 'Currency exchange rate for %s/%s not found';

    public function __construct(
        private Currency $baseCurrency,
        CurrencyExchangeRate ...$exchangeRates
    ) {
        $this->exchangeRates = $exchangeRates;
    }

    private function getInvertedRate(
        CurrencyExchangeRate $exchangeRate
    ): CurrencyExchangeRate {
        // NOTE: not using bcdiv() because there is no defined precision
        // However, it is OK to use bcdiv() with some constantly very high precision
        $invertedRate = 1 / (float) $exchangeRate->rate;

        return new CurrencyExchangeRate(
            $exchangeRate->target,
            $exchangeRate->source,
            $invertedRate,
        );
    }

    private function getBasicRate(
        Currency $source,
        Currency $target
    ): ?CurrencyExchangeRate {
        if ($source == $target) {
            return new CurrencyExchangeRate($source, $target, 1);
        }

        // Direct search
        foreach ($this->exchangeRates as $exchangeRate) {
            /** @var CurrencyExchangeRate $exchangeRate */
            if ($exchangeRate->source == $source && $exchangeRate->target == $target) {
                return $exchangeRate;
            }
        }

        // Only if direct exchange pair is not found, try inverted
        foreach ($this->exchangeRates as $exchangeRate) {
            if ($exchangeRate->target == $source && $exchangeRate->source == $target) {
                return $this->getInvertedRate($exchangeRate);
            }
        }

        return null;
    }

    private function getCrossRate(
        Currency $source,
        Currency $target
    ): CurrencyExchangeRate {
        $sourceToBase = $this->getBasicRate($source, $this->baseCurrency);
        $baseToTarget = $this->getBasicRate($this->baseCurrency, $target);

        if (null !== $sourceToBase && null !== $baseToTarget) {
            // NOTE: not using bcdiv(), see getInvertedRate() for details
            $crossRate = (float) $sourceToBase->rate * (float) $baseToTarget->rate;

            return new CurrencyExchangeRate($source, $target, $crossRate);
        }

        throw new \RuntimeException(sprintf(self::ERROR_NOT_FOUND, $source->value, $target->value));
    }

    public function getCurrencyExchangeRate(
        Currency $source,
        Currency $target
    ): CurrencyExchangeRate {
        return $this->getBasicRate($source, $target) ?? $this->getCrossRate($source, $target);
    }
}
