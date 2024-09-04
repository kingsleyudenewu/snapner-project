<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case PENDING = 'PENDING';
    case SUSPENDED = 'SUSPENDED';
}
