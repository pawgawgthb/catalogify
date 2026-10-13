<?php

declare(strict_types=1);

namespace App\Security\Service;

use App\Security\DTO\UserRegistrationRequest;
use App\Security\Entity\User;
use App\Security\Exception\UserAlreadyExistsException;
use App\Security\Factory\UserFactory;
use App\Security\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserManager
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserFactory $userFactory,
    ) {
    }

    public function register(UserRegistrationRequest $request): User
    {
        $email = mb_strtolower(trim($request->getEmail()));

        if ($this->userRepository->findOneBy(['email' => $email]) !== null) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->userFactory->create($email, $request->getPassword());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
