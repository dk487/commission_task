<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Helper;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\Operation\OperationType;
use DK487\CommissionTask\Model\Operation\UserType;
use DK487\CommissionTask\Model\UserIdentificator;

class CsvOperationsParser
{
    public static function parseLine(string $csvLine): Operation 
    {
        $csv = str_getcsv($csvLine);

        [
            $date,
            $uid,
            $userType,
            $operationType,
            $amount,
            $currency,
        ] = $csv;
        
        return new Operation(
            new \DateTimeImmutable($date),
            new UserIdentificator((int) $uid),
            UserType::from($userType),
            OperationType::from($operationType),
            new Money(
                $amount,
                Currency::from($currency)
            )
        );
        
    }
}