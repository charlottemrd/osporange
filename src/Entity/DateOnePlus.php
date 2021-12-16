<?php

namespace App\Entity;

use App\Repository\DateOnePlusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateOnePlusRepository::class)
 */
class DateOnePlus
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
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="dateOnePluses")
     */
    private $projet;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemodif1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getDatemodif1(): ?\DateTimeInterface
    {
        return $this->datemodif1;
    }

    public function setDatemodif1(?\DateTimeInterface $datemodif1): self
    {
        $this->datemodif1 = $datemodif1;

        return $this;
    }
}
