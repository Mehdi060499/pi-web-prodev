<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "commart")]
#[ORM\Entity]
class Commart
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: "Articles")]
    #[ORM\JoinColumn(name: "idarticle", referencedColumnName: "idarticle")]
    private ?Articles $idarticle;

    #[ORM\ManyToOne(targetEntity: "Commande")]
    #[ORM\JoinColumn(name: "idcommande", referencedColumnName: "idcommande")]
    private ?Commande $idcommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdarticle(): ?Articles
    {
        return $this->idarticle;
    }

    public function setIdarticle(?Articles $idarticle): static
    {
        $this->idarticle = $idarticle;
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
