<?php

namespace App\Domain\Entity\Site;

use App\Infrastructure\Persistence\Repository\Site\SatisfactorySbpRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SatisfactorySbpRepository::class)]
#[Vich\Uploadable]
class SatisfactorySbp
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'satisfactory_sbp', fileNameProperty: 'sbpName')]
    #[Assert\File(
        mimeTypes: ['application/x-sbp'],
        mimeTypesMessage: 'Veuillez télécharger un fichier SBP valide.'
    )]
    private ?File $sbpFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sbpName = null;

    #[ORM\ManyToOne(inversedBy: 'sbp')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?SatisfactoryBp $satisfactoryBp = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSbpFile(): ?File
    {
        return $this->sbpFile;
    }

    public function setSbpFile(File $sbpFile): static
    {
        $this->sbpFile = $sbpFile;

        if (null !== $sbpFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
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

    public function getSbpName(): ?string
    {
        return $this->sbpName;
    }

    public function setSbpName(?string $sbpName): static
    {
        $this->sbpName = $sbpName;

        return $this;
    }

    public function getSatisfactoryBp(): ?SatisfactoryBp
    {
        return $this->satisfactoryBp;
    }

    public function setSatisfactoryBp(?SatisfactoryBp $satisfactoryBp): static
    {
        $this->satisfactoryBp = $satisfactoryBp;

        return $this;
    }
}
