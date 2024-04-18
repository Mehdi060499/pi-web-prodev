<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "articles")]
#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idarticle", type: "integer", nullable: false)]
    private ?int $idarticle = null;

    #[ORM\Column(name: "nom", type: "string", length: 30, nullable: false)]
    private ?string $nom = null;

    #[ORM\Column(name: "description", type: "string", length: 50, nullable: false)]
    private ?string $description = null;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: false)]
    private ?float $prix = null;

    #[ORM\Column(name: "image", type: "string", length: 100, nullable: false)]
    private ?string $image = null;

    #[ORM\Column(name: "Quantite", type: "integer", nullable: false)]
    private ?int $Quantite = null;

    public function getIdarticle(): ?int
    {
        return $this->idarticle;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $stockage): static
    {
        $this->stockage = $Quantite;

        return $this;
    }
}
