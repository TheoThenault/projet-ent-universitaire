<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'enseignant', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(inversedBy: 'enseignants')]
    #[Assert\Valid]
    private ?StatutEnseignant $StatutEnseignant = null;

    #[ORM\OneToMany(mappedBy: 'enseignant', targetEntity: Cour::class)]
    #[Assert\Valid]
    private Collection $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        // unset the owning side of the relation if necessary
        if ($personne === null && $this->personne !== null) {
            $this->personne->setEnseignant(null);
        }

        // set the owning side of the relation if necessary
        if ($personne !== null && $personne->getEnseignant() !== $this) {
            $personne->setEnseignant($this);
        }

        $this->personne = $personne;

        return $this;
    }

    public function getStatutEnseignant(): ?StatutEnseignant
    {
        return $this->StatutEnseignant;
    }

    public function setStatutEnseignant(?StatutEnseignant $StatutEnseignant): self
    {
        $this->StatutEnseignant = $StatutEnseignant;

        return $this;
    }

    /**
     * @return Collection<int, Cour>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cour $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setEnseignant($this);
        }

        return $this;
    }

    public function removeCour(Cour $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getEnseignant() === $this) {
                $cour->setEnseignant(null);
            }
        }

        return $this;
    }
}
