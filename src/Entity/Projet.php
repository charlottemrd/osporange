<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 */
class Projet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer"  )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domaine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sdomaine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * private $taux = '0';
     */
    private $taux;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isplanningrespecte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $highestphase;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datel1;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date0;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date1;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date2;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date3;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datecrea;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datespec;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datemaj;

    /**
     * @ORM\ManyToOne(targetEntity=Paiement::class, inversedBy="projets")
     */
    private $paiement;

    /**
     * @ORM\ManyToOne(targetEntity=Risque::class, inversedBy="projets")
     */
    private $risque;

    /**
     * @ORM\ManyToOne(targetEntity=TypeBU::class, inversedBy="projets")
     */
    private $typebu;

    /**
     * @ORM\ManyToOne(targetEntity=Priorite::class, inversedBy="projets")
     */
    private $priorite;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="projets")
     */
    private $Phase;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Cout::class, mappedBy="projet")
     */
    private $couts;

    /**
     * @ORM\OneToMany(targetEntity=Modalites::class, mappedBy="projet")
     */
    private $modalites;

    public function __construct()
    {
        $this->couts = new ArrayCollection();
        $this->modalites = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(?string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getSdomaine(): ?string
    {
        return $this->sdomaine;
    }

    public function setSdomaine(?string $sdomaine): self
    {
        $this->sdomaine = $sdomaine;

        return $this;
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

    public function getTaux(): ?int
    {
        return $this->taux;
    }

    public function setTaux(?int $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getIsplanningrespecte(): ?bool
    {
        return $this->isplanningrespecte;
    }

    public function setIsplanningrespecte(?bool $isplanningrespecte): self
    {
        $this->isplanningrespecte = $isplanningrespecte;

        return $this;
    }

    public function getHighestphase(): ?int
    {
        return $this->highestphase;
    }

    public function setHighestphase(?int $highestphase): self
    {
        $this->highestphase = $highestphase;

        return $this;
    }

    public function getDatel1(): ?\DateTimeInterface
    {
        return $this->datel1;
    }

    public function setDatel1(?\DateTimeInterface $datel1): self
    {
        $this->datel1 = $datel1;

        return $this;
    }

    public function getDate0(): ?\DateTimeInterface
    {
        return $this->date0;
    }

    public function setDate0(?\DateTimeInterface $date0): self
    {
        $this->date0 = $date0;

        return $this;
    }

    public function getDate1(): ?\DateTimeInterface
    {
        return $this->date1;
    }

    public function setDate1(?\DateTimeInterface $date1): self
    {
        $this->date1 = $date1;

        return $this;
    }

    public function getDate2(): ?\DateTimeInterface
    {
        return $this->date2;
    }

    public function setDate2(?\DateTimeInterface $date2): self
    {
        $this->date2 = $date2;

        return $this;
    }

    public function getDate3(): ?\DateTimeInterface
    {
        return $this->date3;
    }

    public function setDate3(?\DateTimeInterface $date3): self
    {
        $this->date3 = $date3;

        return $this;
    }

    public function getDatecrea(): ?\DateTimeInterface
    {
        return $this->datecrea;
    }

    public function setDatecrea(?\DateTimeInterface $datecrea): self
    {
        $this->datecrea = $datecrea;

        return $this;
    }

    public function getDatespec(): ?\DateTimeInterface
    {
        return $this->datespec;
    }

    public function setDatespec(?\DateTimeInterface $datespec): self
    {
        $this->datespec = $datespec;

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

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getRisque(): ?Risque
    {
        return $this->risque;
    }

    public function setRisque(?Risque $risque): self
    {
        $this->risque = $risque;

        return $this;
    }

    public function getTypebu(): ?TypeBU
    {
        return $this->typebu;
    }

    public function setTypebu(?TypeBU $typebu): self
    {
        $this->typebu = $typebu;

        return $this;
    }

    public function getPriorite(): ?Priorite
    {
        return $this->priorite;
    }

    public function setPriorite(?Priorite $priorite): self
    {
        $this->priorite = $priorite;

        return $this;
    }

    public function getPhase(): ?Phase
    {
        return $this->Phase;
    }

    public function setPhase(?Phase $Phase): self
    {
        $this->Phase = $Phase;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $cout->setProjet($this);
        }

        return $this;
    }

    public function removeCout(Cout $cout): self
    {
        if ($this->couts->removeElement($cout)) {
            // set the owning side to null (unless already changed)
            if ($cout->getProjet() === $this) {
                $cout->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Modalites[]
     */
    public function getModalites(): Collection
    {
        return $this->modalites;
    }

    public function addModalite(Modalites $modalite): self
    {
        if (!$this->modalites->contains($modalite)) {
            $this->modalites[] = $modalite;
            $modalite->setProjet($this);
        }

        return $this;
    }

    public function removeModalite(Modalites $modalite): self
    {
        if ($this->modalites->removeElement($modalite)) {
            // set the owning side to null (unless already changed)
            if ($modalite->getProjet() === $this) {
                $modalite->setProjet(null);
            }
        }

        return $this;
    }




}
