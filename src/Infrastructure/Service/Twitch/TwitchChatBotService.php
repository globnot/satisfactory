<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchChatBotInterface;
use App\Application\Interface\Twitch\TwitchChatVoteInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Irc\JoinEvent;
use GhostZero\Tmi\Events\Twitch\MessageEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TwitchChatBotService implements TwitchChatBotInterface
{
    private Client $client;

    public function __construct(
        private TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private TwitchChatVoteInterface $twitchChatVoteInterface,
        private ParameterBagInterface $parameterBag,
        private ManagerRegistry $doctrine,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function run(): void
    {
        try {
            $accessToken = $this->twitchAccessTokenInterface->getValidAccessToken();

            if (!$accessToken) {
                $this->logger->warning('Redirection vers la connexion nécessaire.');

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
                    'username' => $this->parameterBag->get('twitch.username'),
                    'password' => 'oauth:'.$accessToken,
                ],
                'channels' => [$this->parameterBag->get('twitch.channel')],
            ]);

            $this->client = new Client($options);

            // Gestionnaire pour les messages
            $this->client->on(MessageEvent::class, [$this, 'handleMessage']);

            // Gestionnaire pour l'événement de connexion au canal
            $this->client->on(JoinEvent::class, [$this, 'handleJoin']);

            $this->client->connect();
        } catch (\Exception $e) {
            $this->logger->error('Erreur globale : '.$e->getMessage());
        }
    }

    public function handleJoin(JoinEvent $event): void
    {
        // Vérifier que c'est le bot qui a rejoint le canal
        if ($event->user === $this->parameterBag->get('twitch.username')) {
            $channel = $this->parameterBag->get('twitch.channel');
            $connectMessage = $this->parameterBag->get('twitch.connect_message');

            for ($i = 0; $i < 5; ++$i) {
                $this->client->say($channel, $connectMessage);
            }
            $this->logger->info('Message de connexion envoyé dans le chat.');
        }
    }

    public function handleMessage(MessageEvent $event): void
    {
        try {
            $message = trim($event->message);
            $username = $event->user;

            $this->logger->info("Message reçu de $username: $message");

            if (preg_match('/^!(\d+)$/', $message, $matches)) {
                $guess = (int) $matches[1];

                // Enregistrement du vote
                $success = $this->twitchChatVoteInterface->registerVote($username, $guess);

                if ($success) {
                    $confirmationMessage = "[GUESS MY LOOT] Merci $username ! Ta prédiction ($guess) a été enregistrée.";
                } else {
                    $confirmationMessage = "[GUESS MY LOOT] Attention $username, tu as déjà voté !";
                    $this->resetEntityManager();
                }

                // Envoi du message dans le chat
                $this->client->say($this->parameterBag->get('twitch.channel'), $confirmationMessage);
            }
        } catch (\Exception $e) {
            $this->logger->error('Erreur dans le gestionnaire d\'événements : '.$e->getMessage());
            $this->resetEntityManager();
        }
    }

    private function resetEntityManager(): void
    {
        if (!$this->entityManager->isOpen()) {
            $this->doctrine->resetManager();
            $this->logger->info('EntityManager réinitialisé.');
        }
    }
}
