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
        if (OperationType::Deposit == $operation->operationType) {
            return self::COMMISSION_RATE_DEPOSIT;
        }

        if (UserType::Business == $operation->userType) {
            return self::COMMISSION_RATE_WITHDRAW_BUSINESS;
        }

        return self::COMMISSION_RATE_WITHDRAW_PRIVATE;
    }

    public function getCommissionFee(Operation $operation): Money
    {
        $applicableMoney = $operation->money;
        $commissionRate = $this->getCommissionBaseRate($operation);

        if (UserType::Private == $operation->userType
            && OperationType::Withdraw == $operation->operationType) {
            $previousOperations = iterator_to_array($this->operationRepository->findSameWeek($operation));

            if (count($previousOperations) < self::WITHDRAW_PRIVATE_FREE_OPERATIONS_PER_WEEK) {
                // How much we can withdraw for free?
                $maxExemption = $this->withdrawPrivateFree;
                foreach ($previousOperations as $previousOperation) {
                    $maxExemption = $this->currencyConvertor->sub($maxExemption, $previousOperation->money);
                }
                $maxExemption = $this->currencyConvertor->minZero($maxExemption);

                // How much is the amount where commission is applicable?
                $applicableMoney = $this->currencyConvertor->sub($applicableMoney, $maxExemption);
                $applicableMoney = $this->currencyConvertor->minZero($applicableMoney);
            }
        }

        $moneyWithoutComission = $this->currencyConvertor->mul($applicableMoney, 1 - $commissionRate);
        $commissionRoundedUp = $this->currencyConvertor->sub($applicableMoney, $moneyWithoutComission);

        return $commissionRoundedUp;
    }

    public function addOperation(Operation $operation): void
    {
        $this->operationRepository->addOperation($operation);
    }
}
