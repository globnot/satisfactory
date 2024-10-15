<?php

namespace App\Infrastructure\Controller\Twitch\Overlay;

use App\Application\Interface\Twitch\TwitchApiInterface;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;
use App\Infrastructure\Service\Twitch\TwitchGetSubscriberCountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchOverlayWebcamController extends AbstractController
{
    public function __construct(
        private TwitchApiInterface $twitchApiInterface,
        private TwitchGetSubscriberCountService $twitchGetSubscriberCountService,
        private TwitchTokenStorageService $twitchTokenStorageService,
    ) {
    }

    #[Route('/twitch/overlay/webcam', name: 'twitch_overlay_webcam')]
    public function getSubscribers(
        Request $request,
    ): Response {
        $tokens = $this->twitchTokenStorageService->getTokens();
        $accessToken = $tokens['access_token'] ?? null;
        $refreshToken = $tokens['refresh_token'] ?? null;
        $expiresAt = $tokens['expires_at'] ?? null;

        if (!$accessToken || !$expiresAt || $expiresAt < time()) {
            if ($refreshToken) {
                try {
                    $tokens = $this->twitchApiInterface->refreshAccessToken($refreshToken);
                    // Mettre à jour les jetons dans le stockage
                    $this->twitchTokenStorageService->updateTokens($tokens);
                    $accessToken = $tokens['access_token'];
                } catch (\Exception $e) {
                    // Rediriger vers la page de connexion si le rafraîchissement échoue
                    return $this->redirectToRoute('twitch_login');
                }
            } else {
                // Aucun refresh token disponible, rediriger vers la page de connexion
                return $this->redirectToRoute('twitch_login');
            }
        }

        try {
            $subscriberCount = $this->twitchGetSubscriberCountService->getSubscriberCount($accessToken);
        } catch (\Exception $e) {
            // Gérer les erreurs, par exemple en affichant un message à l'utilisateur
            return new Response('Erreur lors de la récupération des abonnés.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->render(
            'twitch/overlay/webcam/index.html.twig',
            [
                'subscriberCount' => $subscriberCount,
            ]
        );
    }
}
