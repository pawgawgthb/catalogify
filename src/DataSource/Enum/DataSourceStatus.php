<?php

declare(strict_types=1);

namespace App\DataSource\Enum;

enum DataSourceStatus: string
{
    case READY = 'ready';
    case INVALID = 'invalid';
    case PENDING = 'pending';
}
