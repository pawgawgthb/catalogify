<?php

declare(strict_types=1);

namespace App\Tests\DataSource\Controller;

use App\DataSource\Controller\AttributeTypesController;
use App\Tests\DataSource\Helper\AttributeTypeRegistryHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AttributeTypesControllerTest extends KernelTestCase
{
    public function testReturnListOfAttributeTypes(): void
    {
        $registry = AttributeTypeRegistryHelper::getRegistry();
        $controller = new AttributeTypesController();

        $content = json_decode($controller->__invoke($registry)->getContent(), true);

        self::assertIsArray($content);
        self::assertCount(count($registry->all()), $content['items']);
    }
}
