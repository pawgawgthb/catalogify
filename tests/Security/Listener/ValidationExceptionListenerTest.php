<?php

declare(strict_types=1);

namespace App\Tests\Security\Listener;

use App\Security\DTO\UserRegistrationRequest;
use App\Security\Listener\ValidationExceptionListener;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ValidationExceptionListenerTest extends TestCase
{
    #[DataProvider('violationsProvider')]
    public function testReturnsViolations(ConstraintViolationInterface $violation): void
    {
        $kernel = $this->createStub(HttpKernelInterface::class);
        $request = Request::create(
            uri: '/api/register',
            method: 'POST',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
        );
        $violations = new ConstraintViolationList([$violation]);
        $validationException = new ValidationFailedException(
            value: null,
            violations: $violations,
        );
        $exception = new UnprocessableEntityHttpException(previous: $validationException);
        $event = new ExceptionEvent(
            $kernel,
            $request,
            HttpKernelInterface::MAIN_REQUEST,
            $exception,
        );

        $listener = new ValidationExceptionListener();
        $listener($event);
        $response = $event->getResponse();

        self::assertNotNull($response);
        self::assertSame(422, $response->getStatusCode());

        $data = json_decode(
            (string) $response->getContent(),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        self::assertSame(422, $data['status']);
        self::assertSame('Validation failed.', $data['message']);
        self::assertSame($violation->getPropertyPath(), $data['errors'][0]['property']);
        self::assertSame((string) $violation->getMessage(), $data['errors'][0]['message']);
    }

    /**
     * @return array<string, array{ConstraintViolationInterface}>
     */
    public static function violationsProvider(): array
    {
        return [
            'invalid email' => [
                new ConstraintViolation(
                    message: 'This value is not a valid email address.',
                    messageTemplate: null,
                    parameters: [],
                    root: null,
                    propertyPath: 'email',
                    invalidValue: 'admin',
                ),
            ],
            'empty email' => [
                new ConstraintViolation(
                    message: 'This value should not be blank.',
                    messageTemplate: null,
                    parameters: [],
                    root: null,
                    propertyPath: 'email',
                    invalidValue: '',
                ),
            ],
            'password too short' => [
                new ConstraintViolation(
                    message: str_replace(
                        '{{ limit }}',
                        (string) UserRegistrationRequest::PASSWORD_MIN_LENGTH,
                        UserRegistrationRequest::PASSWORD_MIN_LENGTH_MESSAGE,
                    ),
                    messageTemplate: null,
                    parameters: [
                        '{{ limit }}' => (string) UserRegistrationRequest::PASSWORD_MIN_LENGTH,
                    ],
                    root: null,
                    propertyPath: 'password',
                    invalidValue: str_repeat('a', UserRegistrationRequest::PASSWORD_MIN_LENGTH - 1),
                ),
            ],
            'password too long' => [
                new ConstraintViolation(
                    message: str_replace(
                        '{{ limit }}',
                        (string) UserRegistrationRequest::PASSWORD_MAX_LENGTH,
                        UserRegistrationRequest::PASSWORD_MAX_LENGTH_MESSAGE,
                    ),
                    messageTemplate: null,
                    parameters: [
                        '{{ limit }}' => (string) UserRegistrationRequest::PASSWORD_MAX_LENGTH,
                    ],
                    root: null,
                    propertyPath: 'password',
                    invalidValue: str_repeat('a', UserRegistrationRequest::PASSWORD_MAX_LENGTH + 1),
                ),
            ],
            'weak password' => [
                new ConstraintViolation(
                    message: UserRegistrationRequest::PASSWORD_REGEX_MESSAGE,
                    messageTemplate: null,
                    parameters: [],
                    root: null,
                    propertyPath: 'password',
                    invalidValue: 'password',
                )
            ],
        ];
    }
}
