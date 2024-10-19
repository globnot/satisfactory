<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchApiInterface;
use App\Application\Interface\Twitch\TwitchSubscriberInterface;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;

class TwitchSubscriberService implements TwitchSubscriberInterface
{
    public function __construct(
        private TwitchApiInterface $twitchApiInterface,
        private TwitchGetSubscriberCountService $twitchGetSubscriberCountService,
        private TwitchTokenStorageService $twitchTokenStorageService,
    ) {
    }

    public function getSubscriberCount(): array
    {
        $tokens = $this->twitchTokenStorageService->getTokens();
        $accessToken = $tokens['access_token'] ?? null;
        $refreshToken = $tokens['refresh_token'] ?? null;
        $expiresAt = $tokens['expires_at'] ?? null;

        if (!$accessToken || !$expiresAt || $expiresAt < time()) {
            if ($refreshToken) {
                try {
                    $tokens = $this->twitchApiInterface->refreshAccessToken($refreshToken);
                    $this->twitchTokenStorageService->updateTokens($tokens);
                    $accessToken = $tokens['access_token'];
                } catch (\Exception $e) {
                    return ['error' => 'Redirection vers la connexion nécessaire.'];
                }
            } else {
                return ['error' => 'Redirection vers la connexion nécessaire.'];
            }
        }

        try {
            $subscriberCount = $this->twitchGetSubscriberCountService->getSubscriberCount($accessToken);

            return ['subscriberCount' => $subscriberCount];
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors de la récupération des abonnés.'];
        }
    }
}
