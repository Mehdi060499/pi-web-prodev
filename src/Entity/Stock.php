<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock", indexes={@ORM\Index(name="stock_vendeur", columns={"Idvendeur"})})
 * @ORM\Entity
 */
#[ORM\Table(name: "Stock")]
#[ORM\Entity]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\OneToOne(targetEntity: "Vendeur")]
    #[ORM\JoinColumn(name: "IdVendeur", referencedColumnName: "IdVendeur")]
    private ?Vendeur $IdVendeur;


    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idstock", type: "integer", nullable: false)]
    private ?int  $idstock = null;

    #[ORM\Column(name: "nomproduit", type: "string", length: 255, nullable: false)]
    private $nomproduit;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private ?int $quantite;

    #[ORM\Column(name: "prixUnite", type: "float", nullable: false)]
    private ?float $prixUnite;


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
        return $this->prixUnite;
    }

    public function setPrixUnite(float $prixUnite): static
    {
        $this->prixUnite = $prixUnite;

        return $this;
    }

    public function getIdvendeur(): ?Vendeur
    {
        return $this->idvendeur;
    }

    public function setIdvendeur(?Vendeur $idvendeur): static
    {
        $this->idvendeur = $idvendeur;

        return $this;
    }


}
