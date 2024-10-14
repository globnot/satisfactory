<?php

namespace App\Infrastructure\Controller\Twitch;

use App\Application\Service\Twitch\TwitchApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchOverlayController extends AbstractController
{
    public function __construct(
        private TwitchApiService $twitchApiService,
    ) {
    }

    #[Route('/twitch/overlay', name: 'twitch_overlay')]
    public function index(): Response
    {
        try {
            $subscriberCount = $this->twitchApiService->getSubscriberCount();
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to retrieve subscriber count: '.$e->getMessage());
            $subscriberCount = $e->getMessage();
        }

        return $this->render(
            'stream/overlay/index.html.twig',
            [
                'subscriberCount' => $subscriberCount,
            ]
        );
    }
}
