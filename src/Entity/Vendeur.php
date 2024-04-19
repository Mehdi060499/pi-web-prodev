<?php

namespace App\Entity;
use App\Repository\VendeurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator\Constraints as AppAssert;

#[ORM\Entity(repositoryClass: VendeurRepository::class)]
class Vendeur implements UserInterface
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

   #[AppAssert\UniqueEmail]
   #[Assert\NotBlank(message: "email required")]
   #[Assert\Email(message: "email '{{ value }}' not valid.")]
   #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
   private ?string $email = null;

/**
     * @var string The hashed password
     */
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

    public function getMotdepasse(): string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): static
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

      /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->motdepasse;
    }

    public function setPassword(string $password): static
    {
        $this->motdepasse = $password;

        return $this;
    }
    public function getRoles(): array
    {
        // Implémentez la logique pour récupérer les rôles de l'utilisateur
        return ['ROLE_USER']; // Exemple de rôle par défaut
    }

    public function getSalt(): ?string
    {
        // Vous pouvez renvoyer un sel si nécessaire, sinon retournez null
        return null;
    }

    public function eraseCredentials()
    {
        // Vous pouvez implémenter une logique pour effacer les données sensibles si nécessaire
        // Cette méthode peut généralement rester vide
    }
    public function getUsername(): string
    {
        // Implémentez la logique pour récupérer l'identifiant unique de l'utilisateur
        // Par exemple, vous pouvez renvoyer l'adresse e-mail de l'utilisateur
        return $this->email; // Assurez-vous que $this->email correspond à l'identifiant unique de l'utilisateur
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
