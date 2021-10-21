<?php

namespace App\Entity;

use App\Repository\IdmonthbmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IdmonthbmRepository::class)
 */
class Idmonthbm
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
    private $monthyear;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isaccept;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="idmonthbms")
     */
    private $fournisseur;

    /**
     * @ORM\OneToMany(targetEntity=BilanMensuel::class, mappedBy="idmonthbm",orphanRemoval=true, cascade={"persist"})
     */
    private $bilanMensuels;

    public function __construct()
    {
        $this->bilanMensuels = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsaccept(): ?bool
    {
        return $this->isaccept;
    }

    public function setIsaccept(?bool $isaccept): self
    {
        $this->isaccept = $isaccept;

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
     * @return Collection|BilanMensuel[]
     */
    public function getBilanMensuels(): Collection
    {
        return $this->bilanMensuels;
    }

    public function addBilanMensuel(BilanMensuel $bilanMensuel): self
    {
        if (!$this->bilanMensuels->contains($bilanMensuel)) {
            $this->bilanMensuels[] = $bilanMensuel;
            $bilanMensuel->setIdmonthbm($this);
        }

        return $this;
    }

    public function removeBilanMensuel(BilanMensuel $bilanMensuel): self
    {
        if ($this->bilanMensuels->removeElement($bilanMensuel)) {
            // set the owning side to null (unless already changed)
            if ($bilanMensuel->getIdmonthbm() === $this) {
                $bilanMensuel->setIdmonthbm(null);
            }
        }

        return $this;
    }


}
