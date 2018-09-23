<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionToJsonListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        $code = $exception->getCode() ?: 500;

        $responseData = [
            'errors' => [($code === 500) ? 'Service Error' : $exception->getMessage()],
        ];

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}
