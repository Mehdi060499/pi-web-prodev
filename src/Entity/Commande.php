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

    // Les autres propriétés...

    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "idUsers", referencedColumnName: "idClient")]
    private ?Users $idusers;

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    // Les autres méthodes...
}
