<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logins
 *
 * @ORM\Table(name="logins", indexes={@ORM\Index(name="login_vendeur", columns={"IdVendeur"})})
 * @ORM\Entity
 */
class Logins
{
    /**
     * @var int
     *
     * @ORM\Column(name="idlogin", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlogin;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=false)
     */
    private $ip;

    /**
     * @var \Vendeur
     *
     * @ORM\ManyToOne(targetEntity="Vendeur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdVendeur", referencedColumnName="IdVendeur")
     * })
     */
    private $idvendeur;

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
        return $this->idvendeur;
    }

    public function setIdvendeur(?Vendeur $idvendeur): static
    {
        $this->idvendeur = $idvendeur;

        return $this;
    }


}
