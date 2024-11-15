<?php

namespace App\Domain\Entity\Twitch;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatViewerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TwitchChatViewerRepository::class)]
class TwitchChatViewer
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalPoints = null;

    /**
     * @var Collection<int, TwitchChatRanking>
     */
    #[ORM\OneToMany(targetEntity: TwitchChatRanking::class, mappedBy: 'twitchChatViewer')]
    private Collection $twitchChatRankings;

    /**
     * @var Collection<int, TwitchChatVote>
     */
    #[ORM\OneToMany(targetEntity: TwitchChatVote::class, mappedBy: 'twitchChatViewer')]
    private Collection $twitchChatVotes;

    public function __construct()
    {
        $this->twitchChatRankings = new ArrayCollection();
        $this->twitchChatVotes = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTotalPoints(): ?int
    {
        return $this->totalPoints;
    }

    public function setTotalPoints(?int $totalPoints): static
    {
        $this->totalPoints = $totalPoints;

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
            $twitchChatRanking->setTwitchChatViewer($this);
        }

        return $this;
    }

    public function removeTwitchChatRanking(TwitchChatRanking $twitchChatRanking): static
    {
        if ($this->twitchChatRankings->removeElement($twitchChatRanking)) {
            // set the owning side to null (unless already changed)
            if ($twitchChatRanking->getTwitchChatViewer() === $this) {
                $twitchChatRanking->setTwitchChatViewer(null);
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
            $twitchChatVote->setTwitchChatViewer($this);
        }

        return $this;
    }

    public function removeTwitchChatVote(TwitchChatVote $twitchChatVote): static
    {
        if ($this->twitchChatVotes->removeElement($twitchChatVote)) {
            // set the owning side to null (unless already changed)
            if ($twitchChatVote->getTwitchChatViewer() === $this) {
                $twitchChatVote->setTwitchChatViewer(null);
            }
        }

        return $this;
    }
}
