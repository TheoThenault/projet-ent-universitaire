<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'une Formation est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom d'une Formation doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'une Formation ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cursus $cursus = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "L'année de la formation est obligatoire")]
    #[Assert\Range(
        notInRangeMessage: "L'année de la formation doit être entre {{ min }} et {{ max }}",
        min: 0,
        max: 10,
    )]
    private ?int $annee = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Etudiant::class)]
    private Collection $etudiants;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: UE::class)]
    private Collection $ues;

    #[ORM\OneToOne(mappedBy: 'ResponsableFormation', cascade: ['persist', 'remove'])]
    private ?Enseignant $enseignant = null;

    public function __construct()
    {
        $this->ues = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
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

    public function getCursus(): ?Cursus
    {
        return $this->cursus;
    }

    public function setCursus(?Cursus $cursus): self
    {
        $this->cursus = $cursus;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUes(): Collection
    {
        return $this->ues;
    }

    public function addUe(UE $ue): self
    {
        if (!$this->ues->contains($ue)) {
            $this->ues->add($ue);
        }

        return $this;
    }

    public function removeUe(UE $ue): self
    {
        $this->ues->removeElement($ue);

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setFormation($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getFormation() === $this) {
                $etudiant->setFormation(null);
            }
        }

        return $this;
    }

    public function getCursusAndFormationName(): string
    {
        return $this->getNom() . ' - ' . $this->getCursus()->getNom();
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        // unset the owning side of the relation if necessary
        if ($enseignant === null && $this->enseignant !== null) {
            $this->enseignant->setResponsableFormation(null);
        }

        // set the owning side of the relation if necessary
        if ($enseignant !== null && $enseignant->getResponsableFormation() !== $this) {
            $enseignant->setResponsableFormation($this);
        }

        $this->enseignant = $enseignant;

        return $this;
    }
}
