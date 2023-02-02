<?php

namespace App\Entity;

use App\Repository\CursusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CursusRepository::class)]
class Cursus
{
    const NIVEAUX = ['Master', 'Licence'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'un Cursus est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 128,
        minMessage: "Le nom d'un Cursus doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'un Cursus ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le niveau d'un Cursus est obligatoire")]
    #[Assert\Choice(choices: Cursus::NIVEAUX, message: 'Choisissez un niveau valid.')]
    private ?string $niveau = null;

    #[ORM\OneToMany(mappedBy: 'cursus', targetEntity: Formation::class, orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setCursus($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getCursus() === $this) {
                $formation->setCursus(null);
            }
        }

        return $this;
    }

    public function getCursusCompleteName(): string
    {
        return $this->getNom() . ' - ' . $this->getNiveau();
    }
}
