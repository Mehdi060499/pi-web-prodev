<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "livraison")]
#[ORM\Entity]
class Livraison
{
    #[ORM\Column(name: "idlivraison", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idlivraison = null;

    #[ORM\Column(name: "adresse_de_livraison", type: "string", length: 100, nullable: false)]
    private string $adresseDeLivraison;

    #[ORM\ManyToOne(targetEntity: "Commande")]
    #[ORM\JoinColumn(name: "idcommande", referencedColumnName: "idcommande")]
    private ?Commande $idcommande = null;

    public function getIdlivraison(): ?int
    {
        return $this->idlivraison;
    }

    public function getAdresseDeLivraison(): string
    {
        return $this->adresseDeLivraison;
    }

    public function setAdresseDeLivraison(string $adresseDeLivraison): static
    {
        $this->adresseDeLivraison = $adresseDeLivraison;
        return $this;
    }

    public function getIdcommande(): ?Commande
    {
        return $this->idcommande;
    }

    public function setIdcommande(?Commande $idcommande): static
    {
        $this->idcommande = $idcommande;
        return $this;
    }
}
