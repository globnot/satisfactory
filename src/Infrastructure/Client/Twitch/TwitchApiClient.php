<?php

namespace App\Infrastructure\Client\Twitch;

use App\Application\Interface\Twitch\TwitchApiInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchApiClient implements TwitchApiInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $clientId,
        private string $clientSecret,
    ) {
    }

    public function getAuthorizationUrl(string $redirectUri, string $scopes): string
    {
        return 'https://id.twitch.tv/oauth2/authorize?'.http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scopes,
        ]);
    }

    public function exchangeCodeForToken(string $code, string $redirectUri): array
    {
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
            throw new \Exception('Échec lors de l\'obtention du jeton d\'accès');
        }

        return $response->toArray();
    }

    public function refreshAccessToken(string $refreshToken): array
    {
        $response = $this->httpClient->request('POST', 'https://id.twitch.tv/oauth2/token', [
            'body' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Échec lors du rafraîchissement du jeton d\'accès');
        }

        return $response->toArray();
    }
}
