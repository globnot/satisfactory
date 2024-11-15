<?php

namespace App\Domain\Entity\Twitch;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TwitchChatVoteSessionRepository::class)]
class TwitchChatVoteSession
{
    public const STATE_STARTED = 'started';
    public const STATE_SUSPENDED = 'suspended';
    public const STATE_ENDED = 'ended';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = self::STATE_STARTED;

    #[ORM\Column(nullable: true)]
    private ?int $correctAnswer = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    /**
     * @var Collection<int, TwitchChatRanking>
     */
    #[ORM\OneToMany(targetEntity: TwitchChatRanking::class, mappedBy: 'twitchChatVoteSession')]
    private Collection $twitchChatRankings;

    /**
     * @var Collection<int, TwitchChatVote>
     */
    #[ORM\OneToMany(targetEntity: TwitchChatVote::class, mappedBy: 'twitchChatVoteSession')]
    private Collection $twitchChatVotes;

    public function __construct()
    {
        $this->twitchChatRankings = new ArrayCollection();
        $this->twitchChatVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCorrectAnswer(): ?int
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(?int $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): static
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * @return Collection<int, TwitchChatRanking>
     */
    public function getTwitchChatRankings(): Collection
    {
        return $this->twitchChatRankings;
    }

    public function addTwitchChatRanking(TwitchChatRanking $twitchChatRanking): static
    {
        if (!$this->twitchChatRankings->contains($twitchChatRanking)) {
            $this->twitchChatRankings->add($twitchChatRanking);
            $twitchChatRanking->setTwitchChatVoteSession($this);
        }

        return $this;
    }

    public function removeTwitchChatRanking(TwitchChatRanking $twitchChatRanking): static
    {
        if ($this->twitchChatRankings->removeElement($twitchChatRanking)) {
            // set the owning side to null (unless already changed)
            if ($twitchChatRanking->getTwitchChatVoteSession() === $this) {
                $twitchChatRanking->setTwitchChatVoteSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TwitchChatVote>
     */
    public function getTwitchChatVotes(): Collection
    {
        return $this->twitchChatVotes;
    }

    public function addTwitchChatVote(TwitchChatVote $twitchChatVote): static
    {
        if (!$this->twitchChatVotes->contains($twitchChatVote)) {
            $this->twitchChatVotes->add($twitchChatVote);
            $twitchChatVote->setTwitchChatVoteSession($this);
        }

        return $this;
    }

    public function removeTwitchChatVote(TwitchChatVote $twitchChatVote): static
    {
        if ($this->twitchChatVotes->removeElement($twitchChatVote)) {
            // set the owning side to null (unless already changed)
            if ($twitchChatVote->getTwitchChatVoteSession() === $this) {
                $twitchChatVote->setTwitchChatVoteSession(null);
            }
        }

        return $this;
    }
}
