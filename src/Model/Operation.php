<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model;

readonly class Operation
{
    public function __construct(
        public \DateTimeInterface $date,
        public UserIdentificator $user,
        public Operation\UserType $userType,
        public Operation\OperationType $operationType,
        public Money $money,
    ) {
    }
}
