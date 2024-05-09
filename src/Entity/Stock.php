<?php

namespace App\Entity;
use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StockRepository::class)]

class Stock
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idstock", type: "integer", nullable: false)]
    private ?int  $idstock;

    #[ORM\ManyToOne(targetEntity: Vendeur::class)]
    #[ORM\JoinColumn(name: "Idvendeur", referencedColumnName: "IdVendeur")]
    private ?Vendeur $Idvendeur;




    #[ORM\Column(name: "nomproduit", type: "string", length: 255, nullable: false)]
    private ?string $nomproduit;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private ?int $quantite;

    #[ORM\Column(name: "prix_unite", type: "float", nullable: false)]
    private ?float $prix_unite;


    public function getIdstock(): ?int
    {
        return $this->idstock;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(?string $nomproduit): static
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnite(): ?float
    {
        return $this->prix_unite;
    }

    public function setPrixUnite(float $prix_unite): static
    {
        $this->prix_unite = $prix_unite;

        return $this;
    }

    public function getIdvendeur(): ?Vendeur
    {
        return $this->Idvendeur;
    }

    public function setIdvendeur(?Vendeur $Idvendeur): static
    {
        $this->Idvendeur = $Idvendeur;

        return $this;
    }


}
