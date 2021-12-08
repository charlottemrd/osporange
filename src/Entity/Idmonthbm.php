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
     * @ORM\OneToMany(targetEntity=Bilanmensuel::class, mappedBy="idmonthbm",orphanRemoval=true, cascade={"persist"})
     */
    private $bilanmensuels;

    public function __construct()
    {
        $this->bilanmensuels = new ArrayCollection();
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
     * @return Collection|Bilanmensuel[]
     */
    public function getBilanmensuels(): Collection
    {
        return $this->bilanmensuels;
    }

    public function addBilanmensuel(Bilanmensuel $bilanmensuel): self
    {
        if (!$this->bilanmensuels->contains($bilanmensuel)) {
            $this->bilanmensuels[] = $bilanmensuel;
            $bilanmensuel->setIdmonthbm($this);
        }

        return $this;
    }

    public function removeBilanmensuel(Bilanmensuel $bilanmensuel): self
    {
        if ($this->bilanmensuels->removeElement($bilanmensuel)) {
            // set the owning side to null (unless already changed)
            if ($bilanmensuel->getIdmonthbm() === $this) {
                $bilanmensuel->setIdmonthbm(null);
            }
        }

        return $this;
    }


}
