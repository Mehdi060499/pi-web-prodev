<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "client")]
#[ORM\Entity]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\OneToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "idClient", referencedColumnName: "idClient")]
    private ?Users $idclient;

    public function getIdclient(): ?Users
    {
        return $this->idclient;
    }

    public function setIdclient(?Users $idclient): static
    {
        $this->idclient = $idclient;
        return $this;
    }
}
