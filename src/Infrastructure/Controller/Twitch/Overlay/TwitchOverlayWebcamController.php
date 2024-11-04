<?php

namespace App\Infrastructure\Controller\Twitch\Overlay;

use App\Application\Interface\Twitch\TwitchSubscriberInterface;
use App\Domain\Exception\Twitch\TwitchException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchOverlayWebcamController extends AbstractController
{
    public function __construct(
        private TwitchSubscriberInterface $twitchSubscriberInterface,
    ) {
    }

    #[Route('/twitch/overlay/webcam', name: 'twitch_overlay_webcam')]
    public function getSubscribers(
    ): Response {
        return $this->render(
            'twitch/overlay/webcam/index.html.twig'
        );
    }

    #[Route('/twitch/overlay/webcam/subscriber-count', name: 'twitch_overlay_webcam_subscriber_count', methods: ['GET'])]
    public function getSubscriberCount(): JsonResponse
    {
        try {
            $subscriberCount = $this->twitchSubscriberInterface->getSubscriberCount();

            return $this->json(['subscriberCount' => $subscriberCount]);
        } catch (TwitchException $e) {
            // Déterminer le code de statut HTTP en fonction du message d'erreur
            $statusCode = match ($e->getMessage()) {
                'Jeton d\'accès non disponible ou expiré.' => Response::HTTP_UNAUTHORIZED,
                default => Response::HTTP_INTERNAL_SERVER_ERROR,
            };

            return $this->json(['error' => $e->getMessage()], $statusCode);
        }
    }
}
