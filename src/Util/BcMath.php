<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Util;

class BcMath
{
    public static function multiply(
        string|int|float $leftOperand,
        string|int|float $rightOperand,
        int $scale,
    ): string {
        return bcmul(
            (string) $leftOperand,
            (string) $rightOperand,
            $scale,
        );
    }
}