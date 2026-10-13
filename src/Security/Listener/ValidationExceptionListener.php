<?php

declare(strict_types=1);

namespace App\Security\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ValidationExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof UnprocessableEntityHttpException) {
            return;
        }

        $validationException = $exception->getPrevious();
        if (!$validationException instanceof ValidationFailedException) {
            return;
        }

        $errors = [];
        foreach ($validationException->getViolations() as $violation) {
            $errors[] = [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        $event->setResponse(new JsonResponse(
            [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Validation failed.',
                'errors' => $errors,
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
        ));
    }
}
