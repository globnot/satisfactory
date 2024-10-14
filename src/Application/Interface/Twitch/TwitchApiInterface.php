<?php

namespace App\Application\Interface\Twitch;

interface TwitchApiInterface
{
    public function getAuthorizationUrl(string $redirectUri, string $scopes): string;

    public function exchangeCodeForToken(string $code, string $redirectUri): array;

    public function refreshAccessToken(string $refreshToken): array;

    public function getSubscriberCount(string $accessToken): int;
}
