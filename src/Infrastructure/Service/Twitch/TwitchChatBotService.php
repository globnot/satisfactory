<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchChatBotInterface;
use App\Application\Interface\Twitch\TwitchChatVoteInterface;
use App\Configuration\TwitchConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Irc\JoinEvent;
use GhostZero\Tmi\Events\Twitch\MessageEvent;
use Psr\Log\LoggerInterface;

class TwitchChatBotService implements TwitchChatBotInterface
{
    private ?Client $client = null;

    public function __construct(
        private readonly TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private readonly TwitchChatVoteInterface $twitchChatVoteInterface,
        private readonly TwitchConfiguration $twitchConfiguration,
        private readonly ManagerRegistry $managerRegistry,
        private readonly LoggerInterface $loggerInterface,
        private readonly EntityManagerInterface $entityManagerInterface,
    ) {
    }

    public function run(): void
    {
        try {
            $accessToken = $this->twitchAccessTokenInterface->getValidAccessToken();

            if (!$accessToken) {
                $this->loggerInterface->warning('Redirection vers la connexion nécessaire.');

                return;
            }

            $options = new ClientOptions([
                'options' => ['debug' => true],
                'connection' => [
                    'secure' => true,
                    'reconnect' => true,
                    'rejoin' => true,
                ],
                'identity' => [
                    'username' => $this->twitchConfiguration->getUsername(),
                    'password' => 'oauth:' . $accessToken,
                ],
                'channels' => [$this->twitchConfiguration->getChannel()],
            ]);

            $this->client = new Client($options);

            // Gestionnaire pour les messages
            $this->client->on(MessageEvent::class, [$this, 'handleMessage']);

            // Gestionnaire pour l'événement de connexion au canal
            $this->client->on(JoinEvent::class, [$this, 'handleJoin']);

            $this->client->connect();
        } catch (\Throwable $e) {
            $this->loggerInterface->error('Erreur globale : {message}', ['message' => $e->getMessage()]);
        }
    }

    public function handleJoin(JoinEvent $event): void
    {
        try {
            // Vérifier que c'est le bot qui a rejoint le canal
            if (0 === strcasecmp($event->user, $this->twitchConfiguration->getUsername())) {
                $channel = $this->twitchConfiguration->getChannel();
                $connectMessage = $this->twitchConfiguration->getConnectMessage();
                $repeat = $this->twitchConfiguration->getConnectMessageRepeat();

                for ($i = 0; $i < $repeat; ++$i) {
                    $this->client->say($channel, $connectMessage);
                }
                $this->loggerInterface->info('Message de connexion envoyé dans le chat.');
            }
        } catch (\Throwable $e) {
            $this->loggerInterface->error('Erreur lors de la gestion de la jointure : {message}', ['message' => $e->getMessage()]);
        }
    }

    public function handleMessage(MessageEvent $event): void
    {
        try {
            $message = trim($event->message);
            $username = $event->user;

            $this->loggerInterface->info('Message reçu de {username}: {message}', [
                'username' => $username,
                'message' => $message,
            ]);

            if (preg_match('/^!(\d+)$/', $message, $matches)) {
                $guess = (int) $matches[1];

                // Enregistrement du vote
                $success = $this->twitchChatVoteInterface->registerVote($username, $guess);

                if ($success) {
                    $confirmationMessage = sprintf(
                        '[GUESS MY LOOT] Merci %s ! Ta prédiction (%d) a été enregistrée.',
                        $username,
                        $guess
                    );
                } else {
                    $confirmationMessage = sprintf(
                        '[GUESS MY LOOT] Attention %s, tu as déjà voté !',
                        $username
                    );
                    $this->resetEntityManager();
                }

                // Envoi du message dans le chat
                $this->client->say($this->twitchConfiguration->getChannel(), $confirmationMessage);
            } elseif (0 === strcasecmp($message, '!stop') && 0 === strcasecmp($username, $this->twitchConfiguration->getUsername())) {
                $this->stop();
            }
        } catch (\Throwable $e) {
            $this->loggerInterface->error('Erreur dans le gestionnaire d\'événements : {message}', ['message' => $e->getMessage()]);
            $this->resetEntityManager();
        }
    }

    public function stop(): void
    {
        if ($this->client) {
            $this->client->close();
            $this->loggerInterface->info('Client Twitch fermé.');
        }
    }

    private function resetEntityManager(): void
    {
        if (!$this->entityManagerInterface->isOpen()) {
            $this->managerRegistry->resetManager();
            $this->loggerInterface->info('EntityManager réinitialisé.');
        }
    }
}
