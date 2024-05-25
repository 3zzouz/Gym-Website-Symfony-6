<?php

namespace App\Entity;

use App\Repository\OffreClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: OffreClientRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class OffreClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'offreClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offres $offre = null;

    #[ORM\ManyToOne(inversedBy: 'offreClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    /**
     * @return OffreClient
     */
    #[ORM\PrePersist]
    public function ajoutDatesAuto(): static
    {
        $this->setDateDebut(new \DateTime());
        return $this;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getOffre(): ?Offres
    {
        return $this->offre;
    }

    public function setOffre(?Offres $offre): static
    {
        $this->offre = $offre;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }
}
