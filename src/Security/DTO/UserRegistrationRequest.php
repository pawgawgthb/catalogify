<?php

declare(strict_types=1);

namespace App\Security\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserRegistrationRequest
{
    public const int EMAIL_MAX_LENGTH = 180;
    public const int PASSWORD_MAX_LENGTH = 4096;
    public const int PASSWORD_MIN_LENGTH = 8;
    public const string PASSWORD_MIN_LENGTH_MESSAGE = 'The password must contain at least {{ limit }} characters.';
    public const string PASSWORD_MAX_LENGTH_MESSAGE = 'The password cannot contain more than {{ limit }} characters.';
    public const string PASSWORD_REGEX = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^\p{L}\p{N}]).+$/u';
    public const string PASSWORD_REGEX_MESSAGE = 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(max: self::EMAIL_MAX_LENGTH)]
        private string $email,
        #[Assert\NotBlank]
        #[Assert\Length(
            min: self::PASSWORD_MIN_LENGTH,
            max: self::PASSWORD_MAX_LENGTH,
            minMessage: self::PASSWORD_MIN_LENGTH_MESSAGE,
            maxMessage: self::PASSWORD_MAX_LENGTH_MESSAGE,
        )]
        #[Assert\Regex(
            pattern: self::PASSWORD_REGEX,
            message: self::PASSWORD_REGEX_MESSAGE,
        )]
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
