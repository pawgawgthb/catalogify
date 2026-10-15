<?php

declare(strict_types=1);

namespace App\DataSource\Enum;

enum DataSourceType: string
{
    case JSON = 'json';
    case REST = 'rest';
    case GRAPHQL = 'graphql';
}
