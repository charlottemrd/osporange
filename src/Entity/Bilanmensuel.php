<?php

namespace App\Entity;

use App\Repository\BilanmensuelRepository;
use App\Validator\Validbilan;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BilanmensuelRepository::class)
 * @Validbilan()
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemaj;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="bilanmensuels")
     */
    private $projet;

    /**
     * @ORM\OneToMany(targetEntity=Infobilan::class, mappedBy="bilanmensuel",orphanRemoval=true, cascade={"persist"})
     */
    private $infobilans;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $havebeenmodified;

    /**
     * @ORM\ManyToOne(targetEntity=Idmonthbm::class, inversedBy="bilanMensuels")
     */
    private $idmonthbm;



    public function __construct()
    {
        $this->infobilans = new ArrayCollection();



    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * @return Collection|Infobilan[]|null
     */
    public function getInfobilans(): ?Collection
    {
        return $this->infobilans;
    }




    public function addInfobilan(Infobilan $infobilan): self
    {
        if (!$this->infobilans->contains($infobilan)) {
            $this->infobilans[] = $infobilan;
            $infobilan->setBilanmensuel($this);
        }

        return $this;
    }

    public function removeInfobilan(Infobilan $infobilan): self
    {
        if ($this->infobilans->removeElement($infobilan)) {
            // set the owning side to null (unless already changed)
            if ($infobilan->getBilanmensuel() === $this) {
                $infobilan->setBilanmensuel(null);
            }
        }

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

    public function getIdmonthbm(): ?Idmonthbm
    {
        return $this->idmonthbm;
    }

    public function setIdmonthbm(?Idmonthbm $idmonthbm): self
    {
        $this->idmonthbm = $idmonthbm;

        return $this;
    }






}
