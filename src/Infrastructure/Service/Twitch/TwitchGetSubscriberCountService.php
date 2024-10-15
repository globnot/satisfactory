<?php

namespace App\Infrastructure\Service\Twitch;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchGetSubscriberCountService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $clientId,
        private string $broadcasterId,
    ) {
    }

    public function getSubscriberCount(string $accessToken): int
    {
        if (!$accessToken) {
            throw new \Exception('Jeton d\'accès non disponible ou expiré.');
        }

        $response = $this->httpClient->request('GET', 'https://api.twitch.tv/helix/subscriptions', [
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

        if (!isset($data['total'])) {
            throw new \Exception('La réponse de l\'API Twitch ne contient pas le nombre total d\'abonnés.');
        }

        return (int) $data['total'];
    }
}
