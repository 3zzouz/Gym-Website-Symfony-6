<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
#[ORM\UniqueConstraint(name: 'activite_unique', columns: ['jour', 'heure_debut'])]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NomActivite $nom = null;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coach $coach = null;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeActivite $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $jour = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $heureDebut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?NomActivite
    {
        return $this->nom;
    }

    public function setNom(?NomActivite $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    public function setCoach(?Coach $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getType(): ?TypeActivite
    {
        return $this->type;
    }

    public function setType(?TypeActivite $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getJour(): ?int
    {
        return $this->jour;
    }

    public function setJour(int $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeureDebut(): ?int
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(int $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }
}
