<?php

namespace App\Infrastructure\Controller\Twitch;

use App\Infrastructure\Service\Twitch\TwitchApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchAuthTokenController extends AbstractController
{
    public function __construct(
        private TwitchApiService $twitchApiService,
    ) {
    }

    #[Route('/twitch/login', name: 'twitch_login')]
    public function login(): RedirectResponse
    {
        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        $scopes = 'channel:read:subscriptions';

        $url = $this->twitchApiService->getAuthorizationUrl($redirectUri, $scopes);

        return new RedirectResponse($url);
    }

    #[Route('/twitch/auth', name: 'twitch_auth')]
    public function auth(Request $request): Response
    {
        $code = $request->query->get('code');

        if (!$code) {
            return new Response('Code non fourni par Twitch', Response::HTTP_BAD_REQUEST);
        }

        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        try {
            $tokens = $this->twitchApiService->exchangeCodeForToken($code, $redirectUri);
        } catch (\Exception $e) {
            return new Response('Échec lors de l\'obtention du jeton d\'accès', Response::HTTP_BAD_REQUEST);
        }

        $session = $request->getSession();
        $session->set('twitch_access_token', $tokens['access_token']);
        $session->set('twitch_refresh_token', $tokens['refresh_token']);
        $session->set('twitch_token_expires_at', time() + $tokens['expires_in']);

        return $this->redirectToRoute('twitch_overlay');
    }
}
