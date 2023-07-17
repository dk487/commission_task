<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

readonly class Money
{
    public string $amount;

    public function __construct(
        string|int|float $amount,
        public Currency $currency,
    ) {
        $this->amount = bcadd((string) $amount, '0', $currency->getDecimalPoints());
    }

    public function __toString(): string
    {
        return $this->amount . ' ' . $this->currency->value;
    }
}
