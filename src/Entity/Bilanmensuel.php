<?php

namespace App\Entity;

use App\Repository\BilanmensuelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BilanmensuelRepository::class)
 */
class Bilanmensuel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="bilanmensuels")
     */
    private $fournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="bilanmensuels")
     */
    private $projet;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $monthyear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity=Profil::class, inversedBy="bilanmensuels")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $havebeenmodified;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isaccept;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemaj;

    public function __construct()
    {
        $this->profil = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

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

    public function getMonthyear(): ?\DateTimeInterface
    {
        return $this->monthyear;
    }

    public function setMonthyear(?\DateTimeInterface $monthyear): self
    {
        $this->monthyear = $monthyear;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Profil[]
     */
    public function getProfil(): Collection
    {
        return $this->profil;
    }

    public function addProfil(Profil $profil): self
    {
        if (!$this->profil->contains($profil)) {
            $this->profil[] = $profil;
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        $this->profil->removeElement($profil);

        return $this;
    }

    public function getHavebeenmodified(): ?bool
    {
        return $this->havebeenmodified;
    }

    public function setHavebeenmodified(?bool $havebeenmodified): self
    {
        $this->havebeenmodified = $havebeenmodified;

        return $this;
    }

    public function getIsaccept(): ?bool
    {
        return $this->isaccept;
    }

    public function setIsaccept(?bool $isaccept): self
    {
        $this->isaccept = $isaccept;

        return $this;
    }

    public function getDatemaj(): ?\DateTimeInterface
    {
        return $this->datemaj;
    }

    public function setDatemaj(?\DateTimeInterface $datemaj): self
    {
        $this->datemaj = $datemaj;

        return $this;
    }
}
