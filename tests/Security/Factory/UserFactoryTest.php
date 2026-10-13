<?php

declare(strict_types=1);

namespace Factory;

use App\Security\Factory\UserFactory;
use App\Tests\Helper\UserHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    public function testCreatesUser(): void
    {
        $passwordHasher = $this->createConfiguredStub(UserPasswordHasherInterface::class,
            ['hashPassword' => UserHelper::PASSWORD]);
        $factory = new UserFactory($passwordHasher);

        $user = $factory->create(UserHelper::EMAIL, UserHelper::PASSWORD);

        self::assertSame(UserHelper::EMAIL, $user->getEmail());
        self::assertSame(UserHelper::PASSWORD, $user->getPassword());
    }
}
