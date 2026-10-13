<?php

declare(strict_types=1);

namespace App\Security\Controller;

use App\Security\DTO\UserRegistrationRequest;
use App\Security\Exception\UserAlreadyExistsException;
use App\Security\Service\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final readonly class AuthController
{
    public function __construct(private UserManager $userManager)
    {
    }

    #[Route('api/register', name: 'api_register', methods: ['POST'], format: 'json')]
    public function register(
        #[MapRequestPayload] UserRegistrationRequest $request,
    ): JsonResponse {
        try {
            $user = $this->userManager->register($request);
        } catch (UserAlreadyExistsException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return new JsonResponse(
            ['id' => (string) $user->getId()],
            Response::HTTP_CREATED,
        );
    }
}
