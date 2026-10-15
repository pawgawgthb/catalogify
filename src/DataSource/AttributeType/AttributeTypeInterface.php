<?php

declare(strict_types=1);

namespace App\DataSource\AttributeType;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::TAG)]
interface AttributeTypeInterface
{
    public const string TAG = 'data_type.definition';

    public function getDefinition(): AttributeTypeDefinition;
}
