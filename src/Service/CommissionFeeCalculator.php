<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Service;

use DK487\CommissionTask\Model\Currency;
use DK487\CommissionTask\Model\Money;
use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\Operation\OperationType;
use DK487\CommissionTask\Model\Operation\UserType;
use DK487\CommissionTask\Repository\OperationRepository;

class CommissionFeeCalculator
{
    private const COMMISSION_RATE_DEPOSIT = 0.0003;
    private const COMMISSION_RATE_WITHDRAW_PRIVATE = 0.003;
    private const COMMISSION_RATE_WITHDRAW_BUSINESS = 0.005;

    private Money $withdrawPrivateFree;
    private const WITHDRAW_PRIVATE_FREE_OPERATIONS_PER_WEEK = 3;

    public function __construct(
        private CurrencyConvertor $currencyConvertor,
        private ?OperationRepository $operationRepository = null,
    ) {
        if (is_null($this->operationRepository)) {
            $this->operationRepository = new OperationRepository();
        }

        $this->withdrawPrivateFree = new Money(1000, Currency::EUR);
    }

    private function getCommissionBaseRate(Operation $operation): float
    {
        if ($operation->operationType == OperationType::Deposit) {
            return self::COMMISSION_RATE_DEPOSIT;
        }
 
        if ($operation->userType == UserType::Business) {
            return self::COMMISSION_RATE_WITHDRAW_BUSINESS;
        }
        
        return self::COMMISSION_RATE_WITHDRAW_PRIVATE;
    } 

    public function getCommissionFee(Operation $operation): Money
    {
        $correctedAmount = $operation->money->amount;
        $commissionRate = $this->getCommissionBaseRate($operation);

        // TODO: apply 3 free operations
        // TODO: apply 1000 EUR rule

        $amountWithoutComissionRoundedDown = bcmul(
            $correctedAmount,
            (string) (1 - $commissionRate),
            $operation->money->currency->getDecimalPoints(),
        );

        $commissionAmountRoundedUp = bcsub(
            $correctedAmount,
            $amountWithoutComissionRoundedDown,
            $operation->money->currency->getDecimalPoints(),
        );

        return new Money($commissionAmountRoundedUp, $operation->money->currency);
    }

    public function addOperation(Operation $operation): void
    {
        $this->operationRepository->addOperation($operation);
    }
}
