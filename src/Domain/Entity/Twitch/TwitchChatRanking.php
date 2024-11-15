<?php

namespace App\Domain\Entity\Twitch;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatRankingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TwitchChatRankingRepository::class)]
class TwitchChatRanking
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'twitchChatRankings')]
    private ?TwitchChatVoteSession $twitchChatVoteSession = null;

    #[ORM\ManyToOne(inversedBy: 'twitchChatRankings')]
    private ?TwitchChatViewer $twitchChatViewer = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\Column(nullable: true)]
    private ?int $rank = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTwitchChatVoteSession(): ?TwitchChatVoteSession
    {
        return $this->twitchChatVoteSession;
    }

    public function setTwitchChatVoteSession(?TwitchChatVoteSession $twitchChatVoteSession): static
    {
        $this->twitchChatVoteSession = $twitchChatVoteSession;

        return $this;
    }

    public function getTwitchChatViewer(): ?TwitchChatViewer
    {
        return $this->twitchChatViewer;
    }

    public function setTwitchChatViewer(?TwitchChatViewer $twitchChatViewer): static
    {
        $this->twitchChatViewer = $twitchChatViewer;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }
}
