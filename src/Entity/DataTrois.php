<?php

namespace App\Entity;

use App\Repository\DataTroisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataTroisRepository::class)
 */
class DataTrois
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
    private $datet;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="dataTrois")
     */
    private $projet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatet(): ?\DateTimeInterface
    {
        return $this->datet;
    }

    public function setDatet(?\DateTimeInterface $datet): self
    {
        $this->datet = $datet;

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
