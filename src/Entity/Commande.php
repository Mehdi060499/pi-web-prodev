<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "commande")]
#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idcommande", type: "integer", nullable: false)]
    private ?int $idcommande = null;

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "idClient", referencedColumnName: "idClient", nullable: false)]
    private ?Users $idClient;

    #[ORM\Column(name: "date", type: "datetime", nullable: false)]
    private \DateTimeInterface $date;

    #[ORM\Column(name: "type_commande", type: "string", length: 255, nullable: false)]
    private string $typeCommande;

    #[ORM\Column(name: "quantite", type: "integer", nullable: false)]
    private int $quantite;

    #[ORM\Column(name: "total", type: "decimal", precision: 10, scale: 2, nullable: false)]
    private float $total;

    // Les autres propriétés...

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "idUsers", referencedColumnName: "idClient")]
    private ?Users $idusers;

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function getIdClient(): ?Users
    {
        return $this->idClient;
    }

    public function setIdClient(?Users $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTypeCommande(): string
    {
        return $this->typeCommande;
    }

    public function setTypeCommande(string $typeCommande): self
    {
        $this->typeCommande = $typeCommande;

        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }


    // Les autres méthodes...
}
