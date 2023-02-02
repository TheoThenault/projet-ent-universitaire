<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom d'une salle est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 64,
        minMessage: "Le nom d'une salle doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'une salle ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le bâtiment d'une salle est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 64,
        minMessage: "Le bâtiment d'une salle doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le bâtiment d'une salle ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $batiment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 128,
        minMessage: "L'équipement d'une salle doit comporter au moins {{ limit }} caractères.",
        maxMessage: "L'équipement d'une salle ne peut pas comporter plus de {{ limite }} caractères.",
    )]
    private ?string $equipement = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La capacité d'une salle est obligatoire")]
    #[Assert\Range(
        notInRangeMessage: "La capacité d'une salle doit être entre {{ min }} et {{ max }}",
        min: 0,
        max: 1000,
    )]
    private ?int $capacite = null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Cour::class)]
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(string $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getEquipement(): ?string
    {
        return $this->equipement;
    }

    public function setEquipement(?string $equipement): self
    {
        $this->equipement = $equipement;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

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
            $cour->setSalle($this);
        }

        return $this;
    }

    public function removeCour(Cour $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getSalle() === $this) {
                $cour->setSalle(null);
            }
        }

        return $this;
    }
}
