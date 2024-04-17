<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class Users
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

    #[Assert\NotBlank(message: "Le mot de passe est requis")]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit contenir au moins {{ limit }} caractères")]
    #[ORM\Column(name: "motdepasse", type: "string", length: 255, nullable: false)]
    private ?string $motdepasse ;

    #[Assert\NotBlank(message: "L'adresse est requise")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères")]
    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: false)]
    private ?string $adresse ;

    #[AppAssert\UniqueEmail]
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

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): static
    {
        $this->motdepasse = $motdepasse;

        return $this;
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
