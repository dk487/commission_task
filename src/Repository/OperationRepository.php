<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Repository;

use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\UserIdentificator;

class OperationRepository
{
    private array $operations;

    public function addOperation(Operation $operation): void
    {
        $this->operations[] = $operation;
    }

    public function findByUser(UserIdentificator $user)
    {
        foreach ($this->operations as $operation) {
            /** @var Operation $operation */
            if ($operation->user == $user) {
                yield $operation;
            }
        }
    }
}
