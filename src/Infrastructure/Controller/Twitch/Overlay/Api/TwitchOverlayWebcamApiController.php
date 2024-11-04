<?php

namespace App\Infrastructure\Controller\Twitch\Overlay\Api;

use App\Application\Interface\Twitch\TwitchSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TwitchOverlayWebcamApiController extends AbstractController
{
    public function __construct(
        private TwitchSubscriberInterface $twitchSubscriberInterface,
    ) {
    }

    #[Route('/twitch/overlay/webcam/subscriber-count', name: 'twitch_overlay_webcam_subscriber_count', methods: ['GET'])]
    public function getSubscriberCount(): JsonResponse
    {
        $subscriberCount = $this->twitchSubscriberInterface->getSubscriberCount();

        return $this->json(['subscriberCount' => $subscriberCount]);
    }
}
