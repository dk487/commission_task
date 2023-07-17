<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

readonly class UserIdentificator
{
    public function __construct(
        public int $identificator,
    ) {
    }
}
