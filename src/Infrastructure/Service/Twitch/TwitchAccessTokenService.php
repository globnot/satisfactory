<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchApiInterface;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;

class TwitchAccessTokenService implements TwitchAccessTokenInterface
{
    public function __construct(
        private TwitchTokenStorageService $twitchTokenStorageService,
        private TwitchApiInterface $twitchApiInterface,
    ) {
    }

    public function getValidAccessToken(): ?string
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
                    // Vous pouvez loguer l'erreur si nécessaire
                    return null;
                }
            } else {
                return null;
            }
        }

        return $accessToken;
    }
}
