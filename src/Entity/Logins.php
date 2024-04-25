<?php

namespace App\Entity;

use App\Repository\LoginsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: LoginsRepository::class)]
class Logins
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idlogin", type: "integer", nullable: false)]
    private ?int $idlogin;

    #[ORM\Column(name: "ip", type: "string", length: 255, nullable: false)]
    private ?string $ip;

    #[ORM\ManyToOne(targetEntity: Vendeur::class)]
    #[ORM\JoinColumn(name: "IdVendeur", referencedColumnName: "IdVendeur")]
    private ?Vendeur $Idvendeur = null;

    public function getIdlogin(): ?int
    {
        return $this->idlogin;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

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
