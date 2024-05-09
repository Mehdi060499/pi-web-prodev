<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "don")]
#[ORM\Entity]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "iddon", type: "integer", nullable: false)]
    private ?int $iddon;

    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private \DateTimeInterface $date;

    #[ORM\Column(name: "cordinaliter", type: "string", length: 30, nullable: false)]
    private string $cordinaliter;

    #[ORM\ManyToOne(targetEntity: "Commande")]
    #[ORM\JoinColumn(name: "idcommande", referencedColumnName: "idcommande")]
    private ?Commande $idcommande;

    public function getIddon(): ?int
    {
        return $this->iddon;
    }

    // Les autres méthodes...
}
