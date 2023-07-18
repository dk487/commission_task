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
    private const VALID_LINE_RE = '/^\d{4}\-\d{2}-\d{2}\,\d+,(private|business)\,(withdraw|deposit)\,\d+.?\d*\,[A-Z]{3}\s*$/';

    public static function parseLine(string $csvLine): Operation
    {
        if (!preg_match(self::VALID_LINE_RE, $csvLine)) {
            throw new \RuntimeException('Incorrect input file format: ' . trim($csvLine));
        }

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
