<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MovieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{ 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: "Le réalisateur est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $director = null;

    #[Assert\NotBlank(message: "L'année de sortie est obligatoire.")]
   #[Assert\Range(
    min: 1900,
    max: 2026,
    notInRangeMessage: "L'annee doit etre comprise entre {{ 1800 }} et {{ 2027 }}.")]
    #[ORM\Column]
    private ?int $releaseYear = null;
    #[Assert\NotBlank(message: "Le synopsis est obligatoire.")]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $synopsis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getReleaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(int $releaseYear): static
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }
}
