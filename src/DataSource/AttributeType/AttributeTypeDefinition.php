<?php

declare(strict_types=1);

namespace App\DataSource\AttributeType;

use App\DataSource\Enum\AttributeType;

final readonly class AttributeTypeDefinition
{
    /**
     * @param list<string> $acceptedDataTypes
     * @param array<string, mixed> $configurationSchema
     */
    public function __construct(
        public AttributeType $type,
        public string $label,
        public string $description,
        public array $acceptedDataTypes,
        public array $configurationSchema = [],
    ) {
    }

    /**
     * @return array{
     *     type: string,
     *     label: string,
     *     description: string,
     *     acceptedDataTypes: list<string>,
     *     configurationSchema: array<string, mixed>
     * }
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'label' => $this->label,
            'description' => $this->description,
            'acceptedDataTypes' => $this->acceptedDataTypes,
            'configurationSchema' => $this->configurationSchema,
        ];
    }
}
