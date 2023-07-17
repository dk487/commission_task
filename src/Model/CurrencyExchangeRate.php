<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

readonly class CurrencyExchangeRate
{
    public function __construct(
        public Currency $source,
        public Currency $target,
        public string|int|float $rate,
    ) {
    }
}
