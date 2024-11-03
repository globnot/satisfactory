<?php

namespace App\Domain\Entity\Twitch;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TwitchChatVoteRepository::class)]
class TwitchChatVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(nullable: true)]
    private ?int $guess = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getGuess(): ?int
    {
        return $this->guess;
    }

    public function setGuess(?int $guess): static
    {
        $this->guess = $guess;

        return $this;
    }
}
