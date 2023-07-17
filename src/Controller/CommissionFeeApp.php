<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Controller;

use DK487\CommissionTask\Helper\CsvOperationsParser;
use DK487\CommissionTask\Helper\CurrencyExchangeRateLoader;
use DK487\CommissionTask\Service\CommissionFeeCalculator;
use DK487\CommissionTask\Service\CurrencyConvertor;

class CommissionFeeApp
{
    public function __construct(
        private CommissionFeeCalculator $commissionFeeCalculator,
    ) {
    }

    public static function initWithExchangeRateLoader(string $fileName): self
    {
        return new self(
            new CommissionFeeCalculator(
                new CurrencyConvertor(
                    CurrencyExchangeRateLoader::loadJson($fileName)
                ),
            ),
        );
    }

    public function proceedCsv(array $lines): void
    {
        foreach ($lines as $line) {
            $operation = CsvOperationsParser::parseLine($line);
            echo $this->commissionFeeCalculator->getCommissionFee($operation)->amount . PHP_EOL;
            $this->commissionFeeCalculator->addOperation($operation);
        }
    }

    public function proceedCsvFile(string $fileName): void
    {
        $this->proceedCsv(file($fileName));
    }
}
