<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Security\Entity\User;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UserHelper extends TestCase
{
    public const string ID = '00000000-0000-0000-0000-000000000000';
    public const string PASSWORD = 'password';
    public const string EMAIL = 'email@example.com';

    public static function createUser(): User&Stub
    {
        $email = self::EMAIL;
        $password = self::PASSWORD;
        $id = Uuid::fromString(self::ID);

        return self::createConfiguredStub(User::class, [
            'getId' => $id,
            'getEmail' => $email,
            'getPassword' => $password,
        ]);
    }

    public static function getID(): Uuid
    {
        return Uuid::fromString(self::ID);
    }
}
