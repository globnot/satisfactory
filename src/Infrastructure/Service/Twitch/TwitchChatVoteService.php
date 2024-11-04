<?php

namespace App\Infrastructure\Service\Twitch;

use App\Application\Interface\Twitch\TwitchChatVoteInterface;
use App\Domain\Entity\Twitch\TwitchChatVote;
use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TwitchChatVoteService implements TwitchChatVoteInterface
{
    public function __construct(
        private TwitchChatVoteRepository $voteRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    public function registerVote(string $username, int $guess): bool
    {
        if ($this->voteRepository->findOneBy(['username' => $username])) {
            $this->logger->info("L'utilisateur '$username' a déjà voté.");

            return false;
        }

        $vote = new TwitchChatVote();
        $vote->setUsername($username);
        $vote->setGuess($guess);

        try {
            $this->entityManager->persist($vote);
            $this->entityManager->flush();
            $this->logger->info("Vote enregistré pour '$username' avec le guess '$guess'.");

            return true;
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'enregistrement du vote : '.$e->getMessage());

            return false;
        }
    }
}
