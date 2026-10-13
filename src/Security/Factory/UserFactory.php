<?php

declare(strict_types=1);

namespace App\Security\Factory;

use App\Security\Entity\User;
use App\Security\Role\Roles;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles([Roles::ROLE_USER->value]);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $password),
        );

        return $user;
    }
}
