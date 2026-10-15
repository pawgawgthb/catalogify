<?php

declare(strict_types=1);

namespace App\DataSource\Controller;

use App\DataSource\AttributeType\AttributeTypeDefinition;
use App\DataSource\AttributeType\AttributeTypeRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final readonly class AttributeTypesController
{
    #[Route('/api/attribute-types', name: 'api_attribute_types', methods: ['GET'], format: 'json')]
    public function __invoke(AttributeTypeRegistry $registry): JsonResponse
    {
        return new JsonResponse([
                'items' => array_map(
                    static fn (AttributeTypeDefinition $definition): array => $definition->toArray(),
                    $registry->all(),
                )
            ]
        );
    }
}
