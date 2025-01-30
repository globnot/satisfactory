<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Configuration\TwitchConfiguration;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use Psr\Log\LoggerInterface;

class TwitchChatMessageService
{
    private ?Client $client = null;

    public function __construct(
        private readonly TwitchConfiguration $twitchConfiguration,
        private readonly TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private readonly LoggerInterface $loggerInterface,
    ) {
    }

    public function sendMessage(string $message): void
    {
        try {
            $options = new ClientOptions([
                'options' => ['debug' => false],
                'connection' => [
                    'secure' => true,
                    'reconnect' => false,
                ],
                'identity' => [
                    'username' => $this->twitchConfiguration->getUsername(),
                    'password' => 'oauth:'.$this->twitchAccessTokenInterface->getValidAccessToken(),
                ],
                'channels' => [$this->twitchConfiguration->getChannel()],
            ]);

            $this->client = new Client($options);

            $this->client->on('connected', function () use ($message) {
                $channel = $this->twitchConfiguration->getChannel();
                $this->client->say($channel, $message);
            });

            $this->client->connect();
        } catch (\Throwable $e) {
            $this->loggerInterface->error('Erreur lors de l\'envoi du message au chat Twitch : {message}', ['message' => $e->getMessage()]);
        }
    }
}
