<?php

namespace App\Entity;

use App\Repository\DatepvinterneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatepvinterneRepository::class)
 */
class Datepvinterne
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
    private $datemy;

    /**
     * @ORM\OneToMany(targetEntity=Pvinternes::class, mappedBy="date")
     */
    private $pvinternes;

    public function __construct()
    {
        $this->pvinternes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatemy(): ?\DateTimeInterface
    {
        return $this->datemy;
    }

    public function setDatemy(?\DateTimeInterface $datemy): self
    {
        $this->datemy = $datemy;

        return $this;
    }

    /**
     * @return Collection|Pvinternes[]
     */
    public function getPvinternes(): Collection
    {
        return $this->pvinternes;
    }

    public function addPvinterne(Pvinternes $pvinterne): self
    {
        if (!$this->pvinternes->contains($pvinterne)) {
            $this->pvinternes[] = $pvinterne;
            $pvinterne->setDate($this);
        }

        return $this;
    }

    public function removePvinterne(Pvinternes $pvinterne): self
    {
        if ($this->pvinternes->removeElement($pvinterne)) {
            // set the owning side to null (unless already changed)
            if ($pvinterne->getDate() === $this) {
                $pvinterne->setDate(null);
            }
        }

        return $this;
    }
}
