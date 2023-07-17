<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

readonly class Money
{
    public function __construct(
        public string $amount,
        public Currency $currency
    ) {
    }
}
