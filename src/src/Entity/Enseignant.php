<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'enseignant', cascade: ['persist', 'remove'])]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(inversedBy: 'enseignants')]
    private ?StatutEnseignant $StatutEnseignant = null;

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
}
