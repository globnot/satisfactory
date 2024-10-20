<?php

namespace App\Domain\Entity\Site;

use App\Infrastructure\Persistence\Repository\Site\SatisfactoryBpRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SatisfactoryBpRepository::class)]
class SatisfactoryBp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $downloadUrlSbp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $downloadUrlSbpcfg = null;

    #[ORM\Column(nullable: true)]
    private ?int $downloadCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $likeCount = null;

    #[ORM\Column(nullable: true)]
    private ?int $thankCount = null;

    /**
     * @var Collection<int, SatisfactoryImage>
     */
    #[ORM\OneToMany(targetEntity: SatisfactoryImage::class, mappedBy: 'satisfactoryBp', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $image;

    /**
     * @var Collection<int, SatisfactorySbp>
     */
    #[ORM\OneToMany(targetEntity: SatisfactorySbp::class, mappedBy: 'satisfactoryBp', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sbp;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->sbp = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDownloadUrlSbp(): ?string
    {
        return $this->downloadUrlSbp;
    }

    public function setDownloadUrlSbp(?string $downloadUrlSbp): static
    {
        $this->downloadUrlSbp = $downloadUrlSbp;

        return $this;
    }

    public function getDownloadUrlSbpcfg(): ?string
    {
        return $this->downloadUrlSbpcfg;
    }

    public function setDownloadUrlSbpcfg(?string $downloadUrlSbpcfg): static
    {
        $this->downloadUrlSbpcfg = $downloadUrlSbpcfg;

        return $this;
    }

    public function getDownloadCount(): ?int
    {
        return $this->downloadCount;
    }

    public function setDownloadCount(?int $downloadCount): static
    {
        $this->downloadCount = $downloadCount;

        return $this;
    }

    public function getLikeCount(): ?int
    {
        return $this->likeCount;
    }

    public function setLikeCount(?int $likeCount): static
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    public function getThankCount(): ?int
    {
        return $this->thankCount;
    }

    public function setThankCount(?int $thankCount): static
    {
        $this->thankCount = $thankCount;

        return $this;
    }

    /**
     * @return Collection<int, SatisfactoryImage>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(SatisfactoryImage $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setSatisfactoryBp($this);
        }

        return $this;
    }

    public function removeImage(SatisfactoryImage $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getSatisfactoryBp() === $this) {
                $image->setSatisfactoryBp(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SatisfactorySbp>
     */
    public function getSbp(): Collection
    {
        return $this->sbp;
    }

    public function addSbp(SatisfactorySbp $sbp): static
    {
        if (!$this->sbp->contains($sbp)) {
            $this->sbp->add($sbp);
            $sbp->setSatisfactoryBp($this);
        }

        return $this;
    }

    public function removeSbp(SatisfactorySbp $sbp): static
    {
        if ($this->sbp->removeElement($sbp)) {
            // set the owning side to null (unless already changed)
            if ($sbp->getSatisfactoryBp() === $this) {
                $sbp->setSatisfactoryBp(null);
            }
        }

        return $this;
    }
}
