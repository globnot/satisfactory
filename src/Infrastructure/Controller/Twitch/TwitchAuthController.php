<?php

namespace App\Infrastructure\Controller\Twitch;

use App\Infrastructure\Client\Twitch\TwitchApiClient;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchAuthController extends AbstractController
{
    public function __construct(
        private TwitchApiClient $twitchApiClient,
        private TwitchTokenStorageService $tokenStorageService,
    ) {
    }

    #[Route('/twitch/login', name: 'twitch_login')]
    public function login(): RedirectResponse
    {
        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        $scopes = 'channel:read:subscriptions chat:read chat:edit';

        $url = $this->twitchApiClient->getAuthorizationUrl($redirectUri, $scopes);

        return new RedirectResponse($url);
    }

    #[Route('/twitch/auth', name: 'twitch_auth')]
    public function auth(
        Request $request,
    ): Response {
        $code = $request->query->get('code');

        if (!$code) {
            return new Response('Code non fourni par Twitch', Response::HTTP_BAD_REQUEST);
        }

        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        try {
            $tokens = $this->twitchApiClient->exchangeCodeForToken($code, $redirectUri);
            $this->tokenStorageService->updateTokens($tokens);
        } catch (\Exception $e) {
            return new Response('Échec lors de l\'obtention du jeton d\'accès', Response::HTTP_BAD_REQUEST);
        }

        return $this->redirectToRoute('twitch_overlay_webcam');
    }
}
