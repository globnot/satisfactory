<?php

namespace App\Domain\Entity\Site;

use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Persistence\Repository\Site\SatisfactoryBpRepository;

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
}
