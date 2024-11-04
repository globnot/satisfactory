<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Domain\Entity\Twitch\TwitchChatVote;
use Doctrine\ORM\EntityManagerInterface;
use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\MessageEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TwitchChatBotService
{
    private Client $client;

    public function __construct(
        private TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function run()
    {
        $accessToken = $this->twitchAccessTokenInterface->getValidAccessToken();

        if (!$accessToken) {
            echo 'Redirection vers la connexion nécessaire.';

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
