<?php

namespace App\Infrastructure\Controller\Twitch\Overlay;

use App\Application\Interface\Twitch\TwitchApiInterface;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;
use App\Infrastructure\Service\Twitch\TwitchGetSubscriberCountService;
use App\Infrastructure\Service\Twitch\TwitchSubscriberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchOverlayWebcamController extends AbstractController
{
    public function __construct(
        private TwitchApiInterface $twitchApiInterface,
        private TwitchGetSubscriberCountService $twitchGetSubscriberCountService,
        private TwitchTokenStorageService $twitchTokenStorageService,
        private TwitchSubscriberService $twitchSubscriberService,
    ) {
    }

    #[Route('/twitch/overlay/webcam', name: 'twitch_overlay_webcam')]
    public function getSubscribers(
    ): Response {
        return $this->render(
            'twitch/overlay/webcam/index.html.twig'
        );
    }

    #[Route('/twitch/overlay/webcam/subscriber-count', name: 'twitch_overlay_webcam_subscriber_count')]
    public function getSubscriberCount(): JsonResponse
    {
        $result = $this->twitchSubscriberService->getSubscriberCount();

        if (isset($result['error'])) {
            // Vous pouvez ajuster le code de statut en fonction de l'erreur
            $statusCode = match ($result['error']) {
                'Redirection vers la connexion nécessaire.' => Response::HTTP_UNAUTHORIZED,
                default => Response::HTTP_INTERNAL_SERVER_ERROR,
            };

            return new JsonResponse(['error' => $result['error']], $statusCode);
        }

        return new JsonResponse(['subscriberCount' => $result['subscriberCount']]);
    }
}
