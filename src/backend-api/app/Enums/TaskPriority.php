<?php

namespace App\Enums;

enum TaskPriority: int
{
    case Urgent = 4;
    case High = 3;
    case Normal = 2;
    case Low = 1;

    public function label(): string
    {
        return match ($this) {
            self::Urgent => 'Urgent',
            self::High => 'High',
            self::Normal => 'Normal',
            self::Low => 'Low',
        };
    }
}
