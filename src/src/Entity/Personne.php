<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Personne implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: "l'email {{ value }} n'est pas un email valide.",
    )]
    #[Assert\NotBlank(message: "L'email d'une personne est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'une personne est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 64,
        minMessage: "Le nom d'une personne doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'une personne ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom d'une personne est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 64,
        minMessage: "Le prénom d'une personne doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le prénom d'une personne ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $prenom = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    private ?Etudiant $etudiant = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    private ?Enseignant $enseignant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\PostPersist]
    public function setEmailSafe(LifecycleEventArgs $args): void
    {
        $entityManager = $args->getObjectManager();
        $liste_persone = $entityManager->getRepository(Personne::class)->findWhithSameMail($this->prenom, $this->nom);

        if(count($liste_persone) > 1){
            $this->setEmail($this->prenom . '.' . $this->nom . count($liste_persone) . "@univ-poitiers.fr");
        }
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
