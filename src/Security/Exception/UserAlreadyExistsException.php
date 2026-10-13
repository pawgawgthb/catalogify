<?php

declare(strict_types=1);

namespace App\Security\Exception;

use Symfony\Component\HttpFoundation\Response;

final class UserAlreadyExistsException extends \RuntimeException
{
    public const string MESSAGE = 'User already exists.';

    public function __construct(string $message = self::MESSAGE, int $code = Response::HTTP_CONFLICT)
    {
        parent::__construct($message, $code);
    }
}
