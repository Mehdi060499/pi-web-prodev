<?php

namespace App\Entity;
use App\Repository\VendeurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VendeurRepository::class)]
class Vendeur
{ #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "IdVendeur", type: "integer", nullable: false)]
   private ?int $idvendeur =  null;

   #[Assert\NotBlank(message: "Nom Required")]
   #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
   private ?string $nom = null;

   #[Assert\NotBlank(message: "Product name required")]
   #[ORM\Column(name: "nomproduit", type: "string", length: 255, nullable: false)]
   private ?string $nomproduit = null;

   #[Assert\NotBlank(message: "email required")]
   #[Assert\Email(message: "email '{{ value }}' not valid.")]
   #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
   private ?string $email = null;


   #[Assert\NotBlank(message: "password required ")]
   #[Assert\Length(min: 8, minMessage: "password need to be atleast {{ limit }} ")]
   #[ORM\Column(name: "motdepasse", type: "string", length: 255, nullable: false)]
   private ?string $motdepasse = null;

   #[Assert\NotBlank(message: "need a description")]
   #[ORM\Column(name: "description", type: "string", length: 255, nullable: false)]
   private ?string $description = null;

   #[Assert\NotBlank(message: "type of the product required")]
   #[ORM\Column(name: "type", type: "string", length: 255, nullable: false)]
   private ?string $type = null;


   #[Assert\NotBlank(message: "product image not found")]
   #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]
   private ?string $image = null;


    public function getIdvendeur(): ?int
    {
        return $this->idvendeur;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(string $nomproduit): static
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): static
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }


}
