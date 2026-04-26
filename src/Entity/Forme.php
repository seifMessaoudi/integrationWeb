<?php

namespace App\Entity;

use App\Repository\FormeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormeRepository::class)]
class Forme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $longueur = null;

    #[ORM\Column]
    private ?int $largeur = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongueur(): ?int
    {
        return $this->longueur;
    }

    public function setLongueur(int $longueur): static
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?int
    {
        return $this->largeur;
    }

    public function setLargeur(int $largeur): static
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
    public function surface(): int
{
    if ($this->type == "carre" && $this->longueur != $this->largeur) {
        throw new \Exception("Carré invalide : longueur != largeur");
    }
    return $this->longueur * $this->largeur;
}

public function perimetre(): int
{
    if ($this->type == "carre" && $this->longueur != $this->largeur) {
        throw new \Exception("Carré invalide : longueur != largeur");
    }
    return ($this->longueur + $this->largeur) * 2;
}
}
