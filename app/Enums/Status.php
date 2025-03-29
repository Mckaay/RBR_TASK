<?php

declare(strict_types=1);

namespace App\Enums;

enum Status: string
{
    case TO_DO = 'todo';
    case IN_PROGRESS = 'in progress';
    case DONE = 'done';
}
