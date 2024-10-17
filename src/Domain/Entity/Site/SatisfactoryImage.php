<?php

namespace App\Domain\Entity\Site;

use App\Infrastructure\Persistence\Repository\Site\SatisfactoryImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SatisfactoryImageRepository::class)]
#[Vich\Uploadable]
class SatisfactoryImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'satisfactory_bp', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?SatisfactoryBp $satisfactoryBp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): static
    {
        $this->imageName = $imageName;

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
