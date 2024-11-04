<?php

namespace App\Application\Interface\Twitch;

interface TwitchChatVoteInterface
{
    public function registerVote(string $username, int $guess): bool;
}
