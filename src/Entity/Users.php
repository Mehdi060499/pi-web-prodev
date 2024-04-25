<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class Users implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idClient", type: "integer", nullable: false)]
    private ?int $idclient ;

    #[Assert\NotBlank(message: "Le nom est requis")]
    #[Assert\Length(max: 255, maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères")]
    #[ORM\Column(name: "nom", type: "string", length: 255, nullable: false)]
    private ?string $nom ;

    #[Assert\NotBlank(message: "Le prénom est requis")]
    #[Assert\Length(max: 255, maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères")]
    #[ORM\Column(name: "prenom", type: "string", length: 255, nullable: false)]
    private ?string $prenom ;

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: "Le mot de passe est requis")]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères")]
    #[ORM\Column(name: "motdepasse", type: "string", length: 255, nullable: false)]
    private ?string $motdepasse ;

  
  
 

    #[Assert\NotBlank(message: "L'adresse est requise")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères")]
    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: false)]
    private ?string $adresse ;

    
    #[Assert\NotBlank(message: "L'email est requis")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]
    private ?string $email ;



    #[Assert\NotBlank(message: "Le rôle est requis")]
#[Assert\Choice(choices: [0, 1, 2], message: "Le rôle doit être 0, 1 ou 2")]
#[ORM\Column(name: "role", type: "integer", nullable: false)]
private ?int $role ;

    public function getIdclient(): ?int
    {
        return $this->idclient;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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


    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

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

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }

   
}
