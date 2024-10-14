<?php

namespace App\Application\Service\Twitch;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchApiService
{
    public function __construct(
        private HttpClientInterface $httpClientInterface,
        private RequestStack $requestStack,
        private string $clientId = '',
        private string $broadcasterId = '',
    ) {
        $this->clientId = $_ENV['TWITCH_CLIENT_ID'] ?? '';
        $this->broadcasterId = $_ENV['TWITCH_BROADCASTER_ID'] ?? '';
    }

    public function getAccessToken(): ?string
    {
        $session = $this->requestStack->getSession();
        $accessToken = $session->get('twitch_access_token');
        $expiresAt = $session->get('twitch_token_expires_at');

        if ($accessToken && $expiresAt && $expiresAt > time()) {
            return $accessToken;
        }

        return null; // Le jeton est expiré ou absent
    }

    public function getSubscriberCount(): int
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            throw new \Exception('Jeton d\'accès non disponible ou expiré.');
        }

        $response = $this->httpClientInterface->request('GET', 'https://api.twitch.tv/helix/subscriptions', [
            'query' => [
                'broadcaster_id' => $this->broadcasterId,
            ],
            'headers' => [
                'Authorization' => 'Bearer '.$accessToken,
                'Client-ID' => $this->clientId,
            ],
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Échec de la récupération du nombre d\'abonnés.');
        }

        $data = $response->toArray();

        return $data['total'] ?? 0;
    }
}
