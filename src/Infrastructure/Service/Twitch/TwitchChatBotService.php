<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchApiInterface;
use App\Domain\Entity\Twitch\TwitchChatVote;
use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteRepository;
use App\Infrastructure\Persistence\Service\Twitch\TwitchTokenStorageService;
use Doctrine\ORM\EntityManagerInterface;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\MessageEvent;

class TwitchChatBotService
{
    private Client $client;

    public function __construct(
        private TwitchChatVoteRepository $twitchChatVoteRepository,
        private TwitchTokenStorageService $twitchTokenStorageService,
        private TwitchApiInterface $twitchApiInterface,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function run()
    {
        // Gestion du token OAuth
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

        $options = new ClientOptions([
            'options' => ['debug' => true],
            'connection' => [
                'secure' => true,
                'reconnect' => true,
                'rejoin' => true,
            ],
            'identity' => [
                'username' => 'globnot',
                'password' => 'oauth:'.$accessToken,
            ],
            'channels' => ['globnot'],
        ]);

        $this->client = new Client($options);

        $this->client->on(MessageEvent::class, function (MessageEvent $event) {
            // if ($event->self)
            //     return;

            $message = $event->message;
            $username = $event->user;

            echo "Message reçu de $username: $message\n";

            if (preg_match('/^!(\d+)$/', $message, $matches)) {
                $number = (int) $matches[1];
                echo "Utilisateur $username a deviné $number\n";

                $vote = new TwitchChatVote();
                $vote->setUsername($username);
                $vote->setGuess($number);

                // Afficher le contenu de $vote pour vérification
                print_r($vote);

                try {
                    $this->entityManager->persist($vote);
                    $this->entityManager->flush();
                    echo "Vote enregistré avec succès pour $username\n";
                } catch (\Exception $e) {
                    echo "Erreur lors de l'enregistrement du vote : ".$e->getMessage()."\n";
                }
            }
        });

        $this->client->connect();
    }
}
