<?php

namespace App\Domain\Entity\Twitch;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TwitchChatVoteRepository::class)]
class TwitchChatVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'twitchChatVotes')]
    private ?TwitchChatViewer $twitchChatViewer = null;

    #[ORM\ManyToOne(inversedBy: 'twitchChatVotes')]
    private ?TwitchChatVoteSession $twitchChatVoteSession = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTwitchChatVoteSession(): ?TwitchChatVoteSession
    {
        return $this->twitchChatVoteSession;
    }

    public function setTwitchChatVoteSession(?TwitchChatVoteSession $twitchChatVoteSession): static
    {
        $this->twitchChatVoteSession = $twitchChatVoteSession;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeImmutable $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
