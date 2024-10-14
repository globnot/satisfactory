<?php

namespace App\Application\Service\Twitch;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchTokenService
{
    private $accessToken;
    private $refreshToken;
    private $tokenExpiresAt;

    public function __construct(
        private HttpClientInterface $httpClientInterface,
        private string $clientId = '',
        private string $clientSecret = '',
    ) {
        $this->clientId = $_ENV['TWITCH_CLIENT_ID'] ?? '';
        $this->clientSecret = $_ENV['TWITCH_CLIENT_SECRET'] ?? '';
        $this->loadTokens();
    }

    private function getTokenFilePath(): string
    {
        // Retourne le chemin complet vers le fichier des jetons
        return __DIR__.'/../../var/twitch_tokens.json';
    }

    private function loadTokens()
    {
        $tokenFile = $this->getTokenFilePath();

        if (!file_exists($tokenFile)) {
            // Le fichier n'existe pas encore, initialisez les variables
            $this->accessToken = null;
            $this->refreshToken = null;
            $this->tokenExpiresAt = 0;

            return;
        }

        $tokens = json_decode(file_get_contents($tokenFile), true);
        $this->accessToken = $tokens['access_token'];
        $this->refreshToken = $tokens['refresh_token'];
        $this->tokenExpiresAt = $tokens['expires_at'];
    }

    private function saveTokens()
    {
        $tokenFile = $this->getTokenFilePath();

        $tokens = [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_at' => $this->tokenExpiresAt,
        ];
        file_put_contents($tokenFile, json_encode($tokens));
    }

    public function getAccessToken(): string
    {
        if ($this->isAccessTokenExpired()) {
            $this->refreshAccessToken();
        }

        return $this->accessToken;
    }

    private function isAccessTokenExpired(): bool
    {
        return $this->tokenExpiresAt <= time();
    }

    private function refreshAccessToken()
    {
        $response = $this->httpClientInterface->request('POST', 'https://id.twitch.tv/oauth2/token', [
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Échec du rafraîchissement du jeton d\'accès.');
        }

        $data = $response->toArray();

        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'] ?? $this->refreshToken;
        $this->tokenExpiresAt = time() + $data['expires_in'];

        $this->saveTokens();
    }
}
