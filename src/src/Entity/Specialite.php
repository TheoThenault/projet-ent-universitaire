<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'une spécialité est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom d'une spécialité doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'une spécialité ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $section = null;

    #[ORM\Column(length: 255)]
    private ?string $groupe = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: UE::class)]
    private Collection $ue;

    #[ORM\OneToMany(mappedBy: 'section', targetEntity: Enseignant::class)]
    private Collection $enseignants;

    public function __construct()
    {
        $this->ue = new ArrayCollection();
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

    public function getSection(): ?int
    {
        return $this->section;
    }

    public function setSection(int $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(string $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUe(): Collection
    {
        return $this->ue;
    }

    public function addUe(UE $ue): self
    {
        if (!$this->ue->contains($ue)) {
            $this->ue->add($ue);
            $ue->setSpecialite($this);
        }

        return $this;
    }

    public function removeUe(UE $ue): self
    {
        if ($this->ue->removeElement($ue)) {
            // set the owning side to null (unless already changed)
            if ($ue->getSpecialite() === $this) {
                $ue->setSpecialite(null);
            }
        }

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
            $enseignant->setSection($this);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): self
    {
        if ($this->enseignants->removeElement($enseignant)) {
            // set the owning side to null (unless already changed)
            if ($enseignant->getSection() === $this) {
                $enseignant->setSection(null);
            }
        }

        return $this;
    }
}
