<?php

namespace App\Infrastructure\Controller\Twitch\Overlay;

use App\Application\Interface\Twitch\TwitchApiInterface;
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
    ) {
    }

    #[Route('/twitch/overlay/webcam', name: 'twitch_overlay_webcam')]
    public function getSubscribers(
        Request $request,
    ): Response {
        $session = $request->getSession();
        $accessToken = $session->get('twitch_access_token');
        $refreshToken = $session->get('twitch_refresh_token');
        $expiresAt = $session->get('twitch_token_expires_at');

        if (!$accessToken || !$expiresAt || $expiresAt < time()) {
            if ($refreshToken) {
                try {
                    $tokens = $this->twitchApiInterface->refreshAccessToken($refreshToken);
                    // Mettre à jour la session avec les nouveaux jetons
                    $session->set('twitch_access_token', $tokens['access_token']);
                    $session->set('twitch_refresh_token', $tokens['refresh_token']);
                    $session->set('twitch_token_expires_at', time() + $tokens['expires_in']);
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
