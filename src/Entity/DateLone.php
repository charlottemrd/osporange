<?php

namespace App\Entity;

use App\Repository\DateLoneRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateLoneRepository::class)
 */
class DateLone
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
    private $datereel;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="dateLones")
     */
    private $projet;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemodifl1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatereel(): ?\DateTimeInterface
    {
        return $this->datereel;
    }

    public function setDatereel(?\DateTimeInterface $datereel): self
    {
        $this->datereel = $datereel;

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

    public function getDatemodifl1(): ?\DateTimeInterface
    {
        return $this->datemodifl1;
    }

    public function setDatemodifl1(?\DateTimeInterface $datemodifl1): self
    {
        $this->datemodifl1 = $datemodifl1;

        return $this;
    }
}
