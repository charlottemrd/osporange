<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @Assert\Regex("/[0-9]+/")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Profil::class, mappedBy="fournisseur", orphanRemoval=true, cascade={"persist"})
     */
    private $profils;

    /**
     * @ORM\OneToMany(targetEntity=Projet::class, mappedBy="fournisseur", orphanRemoval=true)
     */
    private $projets;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $devise;



    /**
     * @ORM\OneToMany(targetEntity=Idmonthbm::class, mappedBy="fournisseur",orphanRemoval=true, cascade={"persist"})
     */
    private $idmonthbms;

    public function __construct()
    {
        $this->profils = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->idmonthbms = new ArrayCollection();
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Profil[]
     */
    public function getProfils(): Collection
    {
        return $this->profils;
    }

    public function addProfil(Profil $profil): self
    {
        if (!$this->profils->contains($profil)) {
            $this->profils[] = $profil;
            $profil->setFournisseur($this);
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        if ($this->profils->removeElement($profil)) {
            // set the owning side to null (unless already changed)
            if ($profil->getFournisseur() === $this) {
                $profil->setFournisseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setFournisseur($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getFournisseur() === $this) {
                $projet->setFournisseur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }


    /**
     * @return Collection|Idmonthbm[]
     */
    public function getIdmonthbms(): Collection
    {
        return $this->idmonthbms;
    }

    public function addIdmonthbm(Idmonthbm $idmonthbm): self
    {
        if (!$this->idmonthbms->contains($idmonthbm)) {
            $this->idmonthbms[] = $idmonthbm;
            $idmonthbm->setFournisseur($this);
        }

        return $this;
    }

    public function removeIdmonthbm(Idmonthbm $idmonthbm): self
    {
        if ($this->idmonthbms->removeElement($idmonthbm)) {
            // set the owning side to null (unless already changed)
            if ($idmonthbm->getFournisseur() === $this) {
                $idmonthbm->setFournisseur(null);
            }
        }

        return $this;
    }






}
