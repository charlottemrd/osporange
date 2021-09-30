<?php

namespace App\Entity;

use App\Repository\DateTwoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DateTwoRepository::class)
 */
class DateTwo
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
    private $datetwo;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="dateTwos")
     */
    private $projet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetwo(): ?\DateTimeInterface
    {
        return $this->datetwo;
    }

    public function setDatetwo(?\DateTimeInterface $datetwo): self
    {
        $this->datetwo = $datetwo;

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
}
