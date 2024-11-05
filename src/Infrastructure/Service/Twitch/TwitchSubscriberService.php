<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchSubscriberInterface;
use App\Configuration\TwitchConfiguration;
use App\Domain\Exception\Twitch\TwitchException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchSubscriberService implements TwitchSubscriberInterface
{
    private const TWITCH_API_URL = 'https://api.twitch.tv/helix/subscriptions';

    public function __construct(
        private HttpClientInterface $httpClient,
        private TwitchAccessTokenInterface $accessTokenProvider,
        private readonly TwitchConfiguration $twitchConfiguration,
    ) {
    }

    public function getSubscriberCount(): int
    {
        $accessToken = $this->accessTokenProvider->getValidAccessToken();

        if (empty($accessToken)) {
            throw new TwitchException('Jeton d\'accès non disponible ou expiré.');
        }

        try {
            $response = $this->httpClient->request('GET', self::TWITCH_API_URL, [
                'query' => [
                    'broadcaster_id' => $this->twitchConfiguration->broadcasterId(),
                ],
                'headers' => [
                    'Authorization' => 'Bearer '.$accessToken,
                    'Client-ID' => $this->twitchConfiguration->clientId(),
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                throw new TwitchException('Échec de la récupération du nombre d\'abonnés.');
            }

            $data = $response->toArray();

            if (!isset($data['total'])) {
                throw new TwitchException('La réponse de l\'API Twitch ne contient pas le nombre total d\'abonnés.');
            }

            return (int) $data['total'];
        } catch (\Exception $e) {
            throw new TwitchException('Erreur lors de la communication avec l\'API Twitch: '.$e->getMessage(), 0, $e);
        }
    }
}
