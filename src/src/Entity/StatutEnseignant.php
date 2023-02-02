<?php

namespace App\Entity;

use App\Repository\StatutEnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatutEnseignantRepository::class)]
class StatutEnseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'un statut enseignant est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 64,
        minMessage: "Le nom d'un statut enseignant doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'un statut enseignant ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: "Heure min d'un statut enseignant doit être entre {{ min }} et {{ max }}",
        min: 0,
        max: 9999,
    )]
    #[Assert\NotBlank(message: "Heure min d'un statut enseignant est obligatoire")]
    private ?int $nbHeureMin = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: "Heure max d'un statut enseignant doit être entre {{ min }} et {{ max }}",
        min: 0,
        max: 9999,
    )]
    #[Assert\NotBlank(message: "Heure max d'un statut enseignant est obligatoire")]
    private ?int $nbHeureMax = null;

    #[ORM\OneToMany(mappedBy: 'StatutEnseignant', targetEntity: Enseignant::class)]
    private Collection $enseignants;

    public function __construct()
    {
        $this->enseignants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNbHeureMin(): ?int
    {
        return $this->nbHeureMin;
    }

    public function setNbHeureMin(int $nbHeureMin): self
    {
        $this->nbHeureMin = $nbHeureMin;

        return $this;
    }

    public function getNbHeureMax(): ?int
    {
        return $this->nbHeureMax;
    }

    public function setNbHeureMax(int $nbHeureMax): self
    {
        $this->nbHeureMax = $nbHeureMax;

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignants(): Collection
    {
        return $this->enseignants;
    }

    public function addEnseignant(Enseignant $enseignant): self
    {
        if (!$this->enseignants->contains($enseignant)) {
            $this->enseignants->add($enseignant);
            $enseignant->setStatutEnseignant($this);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): self
    {
        if ($this->enseignants->removeElement($enseignant)) {
            // set the owning side to null (unless already changed)
            if ($enseignant->getStatutEnseignant() === $this) {
                $enseignant->setStatutEnseignant(null);
            }
        }

        return $this;
    }
}
