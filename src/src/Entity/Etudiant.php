<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'etudiant', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[Assert\Valid]
    private ?Formation $formation = null;

    // Un étudiant à un groupe de TD, de TP et de CM
    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'etudiants')]
    #[Assert\Valid]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: UE::class)]
    #[Assert\Valid]
    private Collection $uesValides;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->uesValides = new ArrayCollection();
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
            $this->personne->setEtudiant(null);
        }

        // set the owning side of the relation if necessary
        if ($personne !== null && $personne->getEtudiant() !== $this) {
            $personne->setEtudiant($this);
        }

        $this->personne = $personne;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->addEtudiant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeEtudiant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUesValides(): Collection
    {
        return $this->uesValides;
    }

    public function addUesValide(UE $uesValide): self
    {
        if (!$this->uesValides->contains($uesValide)) {
            $this->uesValides->add($uesValide);
        }

        return $this;
    }

    public function removeUesValide(UE $uesValide): self
    {
        $this->uesValides->removeElement($uesValide);

        return $this;
    }
}
