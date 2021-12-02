<?php

namespace App\Entity;

use App\Repository\PvinternesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PvinternesRepository::class)
 */
class Pvinternes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isvalidate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pourcentage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ismodified;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="pvinternes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity=Datepvinterne::class, inversedBy="pvinternes")
     */
    private $date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datefin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsvalidate(): ?bool
    {
        return $this->isvalidate;
    }

    public function setIsvalidate(?bool $isvalidate): self
    {
        $this->isvalidate = $isvalidate;

        return $this;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?float $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getIsmodified(): ?bool
    {
        return $this->ismodified;
    }

    public function setIsmodified(?bool $ismodified): self
    {
        $this->ismodified = $ismodified;

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

    public function getDate(): ?Datepvinterne
    {
        return $this->date;
    }

    public function setDate(?Datepvinterne $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }
}
