<?php

declare(strict_types=1);

namespace App\Tests\DataSource\Helper;

use App\DataSource\AttributeType\AttributeTypeRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeTypeRegistryHelper extends KernelTestCase
{
    public static function getRegistry(): AttributeTypeRegistry
    {
        self::bootKernel();

        return self::getContainer()->get('test.attribute_type_registry');
    }
}
