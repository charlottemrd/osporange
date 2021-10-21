<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Regex("/[a-zA-Z]/")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     *
     * @Assert\Regex("/[0-9]/")
     * @ORM\Column(type="float")
     */
    private $tarif;

    /**
     *
     *
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="profils")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fournisseur;

    /**
     * @ORM\OneToMany(targetEntity=Cout::class, mappedBy="profil",orphanRemoval=true, cascade={"persist"})
     */
    private $couts;

    /**
     * @ORM\OneToMany(targetEntity=Infobilan::class, mappedBy="profil",orphanRemoval=true, cascade={"persist"})
     */
    private $infobilans;



    public function __construct()
    {
        $this->couts = new ArrayCollection();
        $this->infobilans = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
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

    /**
     * @return Collection|Cout[]
     */
    public function getCouts(): Collection
    {
        return $this->couts;
    }

    public function addCout(Cout $cout): self
    {
        if (!$this->couts->contains($cout)) {
            $this->couts[] = $cout;
            $cout->setProfil($this);
        }

        return $this;
    }

    public function removeCout(Cout $cout): self
    {
        if ($this->couts->removeElement($cout)) {
            // set the owning side to null (unless already changed)
            if ($cout->getProfil() === $this) {
                $cout->setProfil(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|Infobilan[]
     */
    public function getInfobilans(): Collection
    {
        return $this->infobilans;
    }

    public function addInfobilan(Infobilan $infobilan): self
    {
        if (!$this->infobilans->contains($infobilan)) {
            $this->infobilans[] = $infobilan;
            $infobilan->setProfil($this);
        }

        return $this;
    }

    public function removeInfobilan(Infobilan $infobilan): self
    {
        if ($this->infobilans->removeElement($infobilan)) {
            // set the owning side to null (unless already changed)
            if ($infobilan->getProfil() === $this) {
                $infobilan->setProfil(null);
            }
        }

        return $this;
    }




}
