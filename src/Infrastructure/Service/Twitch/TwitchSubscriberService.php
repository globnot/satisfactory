<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchAccessTokenInterface;
use App\Application\Interface\Twitch\TwitchSubscriberInterface;

class TwitchSubscriberService implements TwitchSubscriberInterface
{
    public function __construct(
        private TwitchAccessTokenInterface $twitchAccessTokenInterface,
        private TwitchGetSubscriberCountService $twitchGetSubscriberCountService,
    ) {
    }

    public function getSubscriberCount(): array
    {
        $accessToken = $this->twitchAccessTokenInterface->getValidAccessToken();

        if (!$accessToken) {
            return ['error' => 'Redirection vers la connexion nécessaire.'];
        }

        try {
            $subscriberCount = $this->twitchGetSubscriberCountService->getSubscriberCount($accessToken);

            return ['subscriberCount' => $subscriberCount];
        } catch (\Exception $e) {
            return ['error' => 'Erreur lors de la récupération des abonnés.'];
        }
    }
}
