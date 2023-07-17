<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Service;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Util\CurrencyExchangeRates;

readonly class CurrencyConvertor
{
    public function __construct(
        readonly public CurrencyExchangeRates $currencyExchangeRates,
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

        $targetAmount = bcmul(
            (string) $sourceMoney->amount,
            (string) $exchangeRate->rate,
            $targetCurrency->getDecimalPoints(),
        );

        return new Money($targetAmount, $targetCurrency);
    }

    public function convertToSameCurrency(Money $leftOperand, Money $rightOperand): Money
    {
        if ($leftOperand->currency != $rightOperand->currency) {
            $rightOperand = $this->convert($rightOperand, $leftOperand->currency);
        }

        return $rightOperand;
    }

    public function add(Money $leftOperand, Money $rightOperand): Money
    {
        $rightOperand = $this->convertToSameCurrency($leftOperand, $rightOperand);

        return new Money(
            bcadd(
                $leftOperand->amount,
                $rightOperand->amount,
                $leftOperand->currency->getDecimalPoints(),
            ),
            $leftOperand->currency,
        );
    }

    public function sub(Money $leftOperand, Money $rightOperand): Money
    {
        $rightOperand = $this->convertToSameCurrency($leftOperand, $rightOperand);

        return new Money(
            bcsub(
                $leftOperand->amount,
                $rightOperand->amount,
                $leftOperand->currency->getDecimalPoints(),
            ),
            $leftOperand->currency,
        );
    }

    public function mul(Money $leftOperand, string|int|float $rightOperand): Money
    {
        return new Money(
            bcmul(
                $leftOperand->amount,
                (string) $rightOperand,
                $leftOperand->currency->getDecimalPoints(),
            ),
            $leftOperand->currency,
        );
    }

    public function minZero(Money $operand): Money
    {
        $compare = bccomp($operand->amount, '0', $operand->currency->getDecimalPoints());

        if ($compare < 0) {
            return new Money(0, $operand->currency);
        }

        return $operand;
    }
}
