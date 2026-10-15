<?php

declare(strict_types=1);

namespace App\DataSource\AttributeType;

use App\DataSource\Enum\AttributeType;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final class AttributeTypeRegistry
{
    /**
     * @var array<string, AttributeTypeDefinition>
     */
    private array $definitions;

    /**
     * @param iterable<AttributeTypeInterface> $dataTypes
     */
    public function __construct(
        #[AutowireIterator(AttributeTypeInterface::TAG)] private readonly iterable $dataTypes,
    ) {
        $definitions = [];

        foreach ($this->dataTypes as $dataType) {
            $definition = $dataType->getDefinition();
            $definitions[$definition->type->value] = $definition;
        }

        $this->definitions = $definitions;
    }

    /**
     * @return AttributeTypeDefinition[]
     */
    public function all(): array
    {
        return array_values($this->definitions);
    }

    public function get(AttributeType $type): AttributeTypeDefinition
    {
        return $this->definitions[$type->value] ?? throw new \LogicException(
            sprintf('No definition registered for attribute type "%s".', $type->value),
        );
    }

    public function has(AttributeType $type): bool
    {
        return isset($this->definitions[$type->value]);
    }
}
