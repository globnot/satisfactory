<?php

namespace App\Application\Interface\Twitch;

interface TwitchAccessTokenInterface
{
    public function getValidAccessToken(): ?string;
}
