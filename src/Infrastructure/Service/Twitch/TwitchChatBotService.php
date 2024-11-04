<?php

// src/Infrastructure/Service/Twitch/TwitchChatBotService.php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchChatBotInterface;
use App\Domain\Entity\Twitch\TwitchChatVote;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\MessageEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TwitchChatBotService implements TwitchChatBotInterface
{
    private Client $client;

    public function __construct(
        private TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
        private ManagerRegistry $doctrine,
        private LoggerInterface $logger,
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

            $this->client->on(MessageEvent::class, [$this, 'handleMessage']);

            $this->client->connect();
        } catch (\Exception $e) {
            $this->logger->error('Erreur globale : '.$e->getMessage());
        }
    }

    public function handleMessage(MessageEvent $event): void
    {
        try {
            $message = $event->message;
            $username = $event->user;

            $this->logger->info(message: "Message reçu de $username: $message");

            if (preg_match('/^!(\d+)$/', $message, $matches)) {
                $number = $matches[1];

                if (!ctype_digit($number)) {
                    $this->logger->error("Erreur : La valeur '$number' n'est pas un entier valide.");

                    return;
                }

                if (bccomp($number, '-2147483648') < 0 || bccomp($number, '2147483647') > 0) {
                    $this->logger->error("Erreur : La valeur '$number' dépasse la plage autorisée pour un entier.");

                    return;
                }

                $number = (int) $number;

                $this->logger->info("$username a enregistré $number");

                $vote = new TwitchChatVote();
                $vote->setUsername($username);
                $vote->setGuess($number);

                $this->persistVote($vote);
            }
        } catch (\Exception $e) {
            $this->logger->error('Erreur dans le gestionnaire d\'événements : '.$e->getMessage());
            $this->resetEntityManager();
        }
    }

    private function persistVote(TwitchChatVote $vote): void
    {
        try {
            $this->entityManager->persist($vote);
            $this->entityManager->flush();
            $this->logger->info("Succès ----> ({$vote->getGuess()}) pour {$vote->getUsername()}");
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'enregistrement du vote : '.$e->getMessage());
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
