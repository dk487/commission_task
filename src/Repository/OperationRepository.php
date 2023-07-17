<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Repository;

use DK487\CommissionTask\Model\Operation;
use DK487\CommissionTask\Model\UserIdentificator;

class OperationRepository
{
    private array $operations = [];

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

    private function getMonday(\DateTimeInterface $date): string
    {
        $date = \DateTimeImmutable::createFromInterface($date);
        $weekday = ((int) $date->format('N')) - 1;

        if ($weekday) {
            $interval = new \DateInterval('P' . $weekday . 'D');
            $date = $date->sub($interval);
        }

        return $date->format('Y-m-d');
    }

    public function findSameWeek(Operation $operation)
    {
        $findWeek = $this->getMonday($operation->date);

        foreach ($this->findByUser($operation->user) as $actualOperation) {
            if ($actualOperation->operationType != $operation->operationType) {
                continue;
            }

            $actualWeek = $this->getMonday($actualOperation->date);

            if ($findWeek == $actualWeek) {
                yield $actualOperation;
            }
        }
    }
}
