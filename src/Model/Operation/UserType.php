<?php

declare(strict_types=1);

namespace DK487\CommissionTask\Model\Operation;

enum UserType: string
{
    case Private = 'private';
    case Business = 'business';
}
