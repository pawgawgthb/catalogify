<?php

declare(strict_types=1);

use App\Security\Controller\AuthController;
use App\Security\DTO\UserRegistrationRequest;
use App\Security\Exception\UserAlreadyExistsException;
use App\Security\Service\UserManager;
use App\Tests\Helper\UserHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends TestCase
{
    private UserManager&MockObject $userManager;
    private AuthController $controller;

    protected function setUp(): void
    {
        $this->userManager = $this->createMock(UserManager::class);
        $this->controller = new AuthController($this->userManager);
    }

    public function testRegistersUser(): void
    {
        $request = new UserRegistrationRequest(UserHelper::EMAIL, UserHelper::PASSWORD);
        $user = UserHelper::createUser();

        $this->userManager
            ->expects(self::once())
            ->method('register')
            ->with($request)
            ->willReturn($user);

        $response = $this->controller->register($request);

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertIsArray($content);
        self::assertArrayHasKey('id', $content);
        self::assertSame((string) $user->getId(), $content['id']);
    }

    public function testRegistersUsers(): void
    {
        $request = new UserRegistrationRequest(UserHelper::EMAIL, UserHelper::PASSWORD);

        $this->userManager
            ->expects(self::once())
            ->method('register')
            ->with($request)
            ->willThrowException(new UserAlreadyExistsException());

        $response = $this->controller->register($request);

        self::assertSame(Response::HTTP_CONFLICT, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertIsArray($content);
        self::assertSame(UserAlreadyExistsException::MESSAGE, $content['message']);
    }
}
