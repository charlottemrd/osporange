<?php

namespace App\Entity;

use App\Repository\DateZeroRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateZeroRepository::class)
 */
class DateZero
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datezero;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="dateZeros")
     */
    private $projet;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemodif0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatezero(): ?\DateTimeInterface
    {
        return $this->datezero;
    }

    public function setDatezero(?\DateTimeInterface $datezero): self
    {
        $this->datezero = $datezero;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getDatemodif0(): ?\DateTimeInterface
    {
        return $this->datemodif0;
    }

    public function setDatemodif0(?\DateTimeInterface $datemodif0): self
    {
        $this->datemodif0 = $datemodif0;

        return $this;
    }
}
