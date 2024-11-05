<?php

namespace App\Configuration;

class TwitchConfiguration
{
    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $broadcasterId,
        private readonly string $username,
        private readonly string $channel,
        private readonly string $connectMessage,
        private readonly int $connectMessageRepeat,
    ) {
    }

    public function clientId(): string
    {
        return $this->clientId;
    }

    public function clientSecret(): string
    {
        return $this->clientSecret;
    }

    public function broadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getConnectMessage(): string
    {
        return $this->connectMessage;
    }

    public function getConnectMessageRepeat(): int
    {
        return $this->connectMessageRepeat;
    }
}
