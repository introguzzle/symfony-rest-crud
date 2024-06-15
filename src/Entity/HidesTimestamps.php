<?php

namespace App\Entity;

trait HidesTimestamps
{
    public function hiddenProperties(): array
    {
        return [
            'created_at',
            'updated_at',
        ];
    }
}