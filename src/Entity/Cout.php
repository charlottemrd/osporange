<?php

namespace App\Entity;

use App\Repository\CoutRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoutRepository::class)
 */
class Cout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="couts")
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="couts")
     */
    private $profil;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nombreprofil;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getNombreprofil(): ?float
    {
        return $this->nombreprofil;
    }

    public function setNombreprofil(?float $nombreprofil): self
    {
        $this->nombreprofil = $nombreprofil;

        return $this;
    }

    public function __toString()
    {
        return $this->getProjet()->getReference().''.$this->getProfil()->getName();
    }

}
