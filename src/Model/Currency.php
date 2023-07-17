<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case JPY = 'JPY';

    private const decimalPoints = [
        'EUR' => 2,
        'USD' => 2,
        'JPY' => 0,
    ];

    public function getDecimalPoints(): int
    {
        return self::decimalPoints[$this->value];
    }
}
