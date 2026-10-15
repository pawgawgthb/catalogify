<?php

declare(strict_types=1);

namespace App\DataSource\AttributeType\Types;

use App\DataSource\AttributeType\AttributeTypeDefinition;
use App\DataSource\AttributeType\AttributeTypeInterface;
use App\DataSource\Enum\AttributeType;

final readonly class Price implements AttributeTypeInterface
{
    public function getDefinition(): AttributeTypeDefinition
    {
        return new AttributeTypeDefinition(
            type: AttributeType::PRICE,
            label: 'Price',
            description: 'Product price',
            acceptedDataTypes: ['integer', 'number'],
        );
    }
}
