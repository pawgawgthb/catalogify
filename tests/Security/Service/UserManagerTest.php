<?php

declare(strict_types=1);

use App\Security\DTO\UserRegistrationRequest;
use App\Security\Exception\UserAlreadyExistsException;
use App\Security\Factory\UserFactory;
use App\Security\Repository\UserRepository;
use App\Security\Service\UserManager;
use App\Tests\Helper\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class UserManagerTest extends TestCase
{
    private UserRepository&MockObject $userRepository;
    private EntityManagerInterface&MockObject $entityManager;
    private UserFactory&MockObject $userFactory;
    private UserManager $userManager;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->userFactory = $this->createMock(UserFactory::class);

        $this->userManager = new UserManager(
            $this->userRepository,
            $this->entityManager,
            $this->userFactory
        );
    }

    public function testRegistersSuccessfully(): void
    {
        $request = new UserRegistrationRequest(UserHelper::EMAIL, UserHelper::PASSWORD);
        $user = UserHelper::createUser();

        $this->userRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['email' => UserHelper::EMAIL])
            ->willReturn(null);
        $this->userFactory
            ->expects(self::once())
            ->method('create')
            ->with(UserHelper::EMAIL, UserHelper::PASSWORD)
            ->willReturn($user);
        $this->entityManager
            ->expects(self::once())
            ->method('persist')
            ->with($user);
        $this->entityManager
            ->expects(self::once())
            ->method('flush');

        $user = $this->userManager->register($request);

        self::assertTrue($user->getId()->equals(UserHelper::getID()));
        self::assertSame(UserHelper::EMAIL, $user->getEmail());
    }

    public function testThrowsExceptionWhenUserAlreadyExists(): void
    {
        $request = new UserRegistrationRequest(UserHelper::EMAIL, UserHelper::PASSWORD);
        $user = UserHelper::createUser();

        self::expectException(UserAlreadyExistsException::class);
        self::expectExceptionMessage('User already exists.');
        self::expectExceptionCode(Response::HTTP_CONFLICT);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneBy')
            ->with(['email' => UserHelper::EMAIL])
            ->willReturn($user);
        $this->userFactory
            ->expects(self::never())
            ->method('create');
        $this->entityManager
            ->expects(self::never())
            ->method('persist');
        $this->entityManager
            ->expects(self::never())
            ->method('flush');

        $this->userManager->register($request);
    }
}
