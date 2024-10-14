<?php

namespace App\Infrastructure\Controller\Twitch;

use App\Application\Service\Twitch\TwitchApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchAuthTokenController extends AbstractController
{
    public function __construct(
        private TwitchApiService $twitchApiService,
        private HttpClientInterface $httpClient,
        private string $clientId = '',
        private string $clientSecret = '',
    ) {
        $this->clientId = $_ENV['TWITCH_CLIENT_ID'] ?? '';
        $this->clientSecret = $_ENV['TWITCH_CLIENT_SECRET'] ?? '';
    }

    #[Route('/twitch/login', name: 'twitch_login')]
    public function login()
    {
        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
        $scopes = 'channel:read:subscriptions';

        $url = 'https://id.twitch.tv/oauth2/authorize?'.http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scopes,
        ]);

        return new RedirectResponse($url);
    }

    #[Route('/twitch/auth', name: 'twitch_auth')]
    public function auth(
        Request $request,
    ) {
        $code = $request->query->get('code');

        if (!$code) {
            return new Response('Code non fourni par Twitch', Response::HTTP_BAD_REQUEST);
        }

        $redirectUri = $this->generateUrl('twitch_auth', [], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

        // Échanger le code contre un jeton d'accès
        $response = $this->httpClient->request('POST', 'https://id.twitch.tv/oauth2/token', [
            'body' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            return new Response('Échec lors de l\'obtention du jeton d\'accès', Response::HTTP_BAD_REQUEST);
        }

        $data = $response->toArray();

        $accessToken = $data['access_token'];
        $refreshToken = $data['refresh_token'];
        $expiresIn = $data['expires_in'];

        // Stocker le jeton d'accès (par exemple, en session ou en base de données)
        $session = $request->getSession();
        $session->set('twitch_access_token', $accessToken);
        $session->set('twitch_refresh_token', $refreshToken);
        $session->set('twitch_token_expires_at', time() + $expiresIn);

        // Rediriger vers la page souhaitée après l'authentification
        return $this->redirectToRoute('twitch_overlay');
    }
}
