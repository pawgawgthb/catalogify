<?php

declare(strict_types=1);

namespace App\DataSource\AttributeType\Types;

use App\DataSource\AttributeType\AttributeTypeDefinition;
use App\DataSource\AttributeType\AttributeTypeInterface;
use App\DataSource\Enum\AttributeType;

final readonly class Image implements AttributeTypeInterface
{
    public function getDefinition(): AttributeTypeDefinition
    {
        return new AttributeTypeDefinition(
            type: AttributeType::IMAGE,
            label: 'Image',
            description: 'Image URL',
            acceptedDataTypes: ['string'],
        );
    }
}
