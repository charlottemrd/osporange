<?php

namespace App\Entity;

use App\Repository\ModalitesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModalitesRepository::class)
 */
class Modalites
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pourcentage;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datefin;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="modalites")
     */
    private $projet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $conditionsatisfield;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conditions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isapproved;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isencours;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rank;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $decisionsapproved;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?float $pourcentage): self
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

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

    public function getConditionsatisfield(): ?bool
    {
        return $this->conditionsatisfield;
    }

    public function setConditionsatisfield(?bool $conditionsatisfield): self
    {
        $this->conditionsatisfield = $conditionsatisfield;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(?string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }
    public function __toString()
    {
        return $this->getConditions();
    }

    public function getIsapproved(): ?bool
    {
        return $this->isapproved;
    }

    public function setIsapproved(?bool $isapproved): self
    {
        $this->isapproved = $isapproved;

        return $this;
    }

    public function getIsencours(): ?bool
    {
        return $this->isencours;
    }

    public function setIsencours(?bool $isencours): self
    {
        $this->isencours = $isencours;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getDecisionsapproved(): ?bool
    {
        return $this->decisionsapproved;
    }

    public function setDecisionsapproved(?bool $decisionsapproved): self
    {
        $this->decisionsapproved = $decisionsapproved;

        return $this;
    }


}
