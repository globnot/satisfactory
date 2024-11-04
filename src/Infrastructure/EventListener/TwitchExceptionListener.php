<?php

namespace App\Infrastructure\EventListener;

use App\Domain\Exception\Twitch\TwitchException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class TwitchExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof TwitchException) {
            return;
        }

        $statusCode = match ($exception->getMessage()) {
            'Jeton d\'accès non disponible ou expiré.' => Response::HTTP_UNAUTHORIZED,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $response = new JsonResponse(['error' => $exception->getMessage()], $statusCode);
        $event->setResponse($response);
    }
}
