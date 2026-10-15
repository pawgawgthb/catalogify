<?php

declare(strict_types=1);

namespace App\Tests\DataSource\AttributeType;

use App\DataSource\AttributeType\AttributeTypeDefinition;
use App\DataSource\AttributeType\AttributeTypeRegistry;
use App\DataSource\Enum\AttributeType;
use App\Tests\DataSource\Helper\AttributeTypeRegistryHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeTypeRegistryTest extends KernelTestCase
{
    private AttributeTypeRegistry $registry;

    public function setUp(): void
    {
        $this->registry = AttributeTypeRegistryHelper::getRegistry();
    }

    public function testRegistryIsAvailableFromContainer(): void
    {
        self::assertCount(count(AttributeType::cases()), $this->registry->all());
    }

    public function testRegistryHasConcreteType(): void
    {
        foreach (AttributeType::cases() as $attributeType) {
            self::assertTrue($this->registry->has($attributeType));
        }
    }

    public function testRegistryReturnsDefinition(): void
    {
        foreach (AttributeType::cases() as $attributeType) {
            self::assertInstanceOf(AttributeTypeDefinition::class, $this->registry->get($attributeType));
        }
    }
}
