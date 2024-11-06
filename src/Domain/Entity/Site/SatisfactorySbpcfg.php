<?php

namespace App\Domain\Entity\Site;

use App\Infrastructure\Persistence\Repository\Site\SatisfactorySbpcfgRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SatisfactorySbpcfgRepository::class)]
#[Vich\Uploadable]
class SatisfactorySbpcfg
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'satisfactory_sbpcfg', fileNameProperty: 'sbpcfgName')]
    #[Assert\File(
        mimeTypes: ['application/x-sbpcfg'],
        mimeTypesMessage: 'Veuillez télécharger un fichier SBPCFG valide.'
    )]
    private ?File $sbpcfgFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sbpcfgName = null;

    #[ORM\ManyToOne(inversedBy: 'sbpcfg')]
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

    public function getSbpcfgFile(): ?File
    {
        return $this->sbpcfgFile;
    }

    public function setSbpcfgFile(File $sbpcfgFile): static
    {
        $this->sbpcfgFile = $sbpcfgFile;

        if (null !== $sbpcfgFile) {
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

    public function getSbpcfgName(): ?string
    {
        return $this->sbpcfgName;
    }

    public function setSbpcfgName(?string $sbpcfgName): static
    {
        $this->sbpcfgName = $sbpcfgName;

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
