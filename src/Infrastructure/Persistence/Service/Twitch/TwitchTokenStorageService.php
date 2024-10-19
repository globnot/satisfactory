<?php

namespace App\Infrastructure\Persistence\Service\Twitch;

use App\Domain\Entity\Twitch\TwitchToken;
use Doctrine\ORM\EntityManagerInterface;

class TwitchTokenStorageService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTokens(): array
    {
        $token = $this->entityManager->getRepository(TwitchToken::class)->findOneBy([]);
        if ($token) {
            return [
                'access_token' => $token->getAccessToken(),
                'refresh_token' => $token->getRefreshToken(),
                'expires_at' => $token->getExpiresAt(),
            ];
        }

        return [];
    }

    public function updateTokens(array $tokens): void
    {
        $token = $this->entityManager->getRepository(TwitchToken::class)->findOneBy([]);
        if (!$token) {
            $token = new TwitchToken();
        }

        $token->setAccessToken($tokens['access_token']);
        $token->setRefreshToken($tokens['refresh_token']);
        $token->setExpiresAt(time() + $tokens['expires_in']);

        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}
