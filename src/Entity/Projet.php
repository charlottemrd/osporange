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
     * @ORM\OneToMany(targetEntity=Cout::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $couts;

    /**
     * @ORM\OneToMany(targetEntity=Modalites::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $modalites;

    /**
     * @ORM\OneToMany(targetEntity=DateLone::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $dateLones;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datereell1;

    /**
     * @ORM\OneToMany(targetEntity=DateZero::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $dateZeros;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datereel0;

    /**
     * @ORM\OneToMany(targetEntity=DateOnePlus::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $dateOnePluses;

    /**
     * @ORM\OneToMany(targetEntity=DateTwo::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $dateTwos;

    /**
     * @ORM\OneToMany(targetEntity=DataTrois::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $dataTrois;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datereel1;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datereel2;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datereel3;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="projet", orphanRemoval=true, cascade={"persist"})
     */
    private $commentaires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $garanti;

    /**
     * @ORM\OneToMany(targetEntity=Bilanmensuel::class, mappedBy="projet",orphanRemoval=true, cascade={"persist"})
     */
    private $bilanmensuels;





    public function __construct()
    {
        $this->couts = new ArrayCollection();
        $this->modalites = new ArrayCollection();
        $this->dateLones = new ArrayCollection();
        $this->dateZeros = new ArrayCollection();
        $this->dateOnePluses = new ArrayCollection();
        $this->dateTwos = new ArrayCollection();
        $this->dataTrois = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->bilanmensuels = new ArrayCollection();


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

    /**
     * @return Collection|DateLone[]
     */
    public function getDateLones(): Collection
    {
        return $this->dateLones;
    }

    public function addDateLone(DateLone $dateLone): self
    {
        if (!$this->dateLones->contains($dateLone)) {
            $this->dateLones[] = $dateLone;
            $dateLone->setProjet($this);
        }

        return $this;
    }

    public function removeDateLone(DateLone $dateLone): self
    {
        if ($this->dateLones->removeElement($dateLone)) {
            // set the owning side to null (unless already changed)
            if ($dateLone->getProjet() === $this) {
                $dateLone->setProjet(null);
            }
        }

        return $this;
    }

    public function getDatereell1(): ?\DateTimeInterface
    {
        return $this->datereell1;
    }

    public function setDatereell1(?\DateTimeInterface $datereell1): self
    {
        $this->datereell1 = $datereell1;

        return $this;
    }

    /**
     * @return Collection|DateZero[]
     */
    public function getDateZeros(): Collection
    {
        return $this->dateZeros;
    }

    public function addDateZero(DateZero $dateZero): self
    {
        if (!$this->dateZeros->contains($dateZero)) {
            $this->dateZeros[] = $dateZero;
            $dateZero->setProjet($this);
        }

        return $this;
    }

    public function removeDateZero(DateZero $dateZero): self
    {
        if ($this->dateZeros->removeElement($dateZero)) {
            // set the owning side to null (unless already changed)
            if ($dateZero->getProjet() === $this) {
                $dateZero->setProjet(null);
            }
        }

        return $this;
    }

    public function getDatereel0(): ?\DateTimeInterface
    {
        return $this->datereel0;
    }

    public function setDatereel0(?\DateTimeInterface $datereel0): self
    {
        $this->datereel0 = $datereel0;

        return $this;
    }

    /**
     * @return Collection|DateOnePlus[]
     */
    public function getDateOnePluses(): Collection
    {
        return $this->dateOnePluses;
    }

    public function addDateOnePlus(DateOnePlus $dateOnePlus): self
    {
        if (!$this->dateOnePluses->contains($dateOnePlus)) {
            $this->dateOnePluses[] = $dateOnePlus;
            $dateOnePlus->setProjet($this);
        }

        return $this;
    }

    public function removeDateOnePlus(DateOnePlus $dateOnePlus): self
    {
        if ($this->dateOnePluses->removeElement($dateOnePlus)) {
            // set the owning side to null (unless already changed)
            if ($dateOnePlus->getProjet() === $this) {
                $dateOnePlus->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DateTwo[]
     */
    public function getDateTwos(): Collection
    {
        return $this->dateTwos;
    }

    public function addDateTwo(DateTwo $dateTwo): self
    {
        if (!$this->dateTwos->contains($dateTwo)) {
            $this->dateTwos[] = $dateTwo;
            $dateTwo->setProjet($this);
        }

        return $this;
    }

    public function removeDateTwo(DateTwo $dateTwo): self
    {
        if ($this->dateTwos->removeElement($dateTwo)) {
            // set the owning side to null (unless already changed)
            if ($dateTwo->getProjet() === $this) {
                $dateTwo->setProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DataTrois[]
     */
    public function getDataTrois(): Collection
    {
        return $this->dataTrois;
    }

    public function addDataTroi(DataTrois $dataTroi): self
    {
        if (!$this->dataTrois->contains($dataTroi)) {
            $this->dataTrois[] = $dataTroi;
            $dataTroi->setProjet($this);
        }

        return $this;
    }

    public function removeDataTroi(DataTrois $dataTroi): self
    {
        if ($this->dataTrois->removeElement($dataTroi)) {
            // set the owning side to null (unless already changed)
            if ($dataTroi->getProjet() === $this) {
                $dataTroi->setProjet(null);
            }
        }

        return $this;
    }

    public function getDatereel1(): ?\DateTimeInterface
    {
        return $this->datereel1;
    }

    public function setDatereel1(?\DateTimeInterface $datereel1): self
    {
        $this->datereel1 = $datereel1;

        return $this;
    }

    public function getDatereel2(): ?\DateTimeInterface
    {
        return $this->datereel2;
    }

    public function setDatereel2(?\DateTimeInterface $datereel2): self
    {
        $this->datereel2 = $datereel2;

        return $this;
    }

    public function getDatereel3(): ?\DateTimeInterface
    {
        return $this->datereel3;
    }

    public function setDatereel3(?\DateTimeInterface $datereel3): self
    {
        $this->datereel3 = $datereel3;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setProjet($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProjet() === $this) {
                $commentaire->setProjet(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getReference();
    }

    public function getGaranti(): ?int
    {
        return $this->garanti;
    }

    public function setGaranti(?int $garanti): self
    {
        $this->garanti = $garanti;

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
            $bilanmensuel->setProjet($this);
        }

        return $this;
    }

    public function removeBilanmensuel(Bilanmensuel $bilanmensuel): self
    {
        if ($this->bilanmensuels->removeElement($bilanmensuel)) {
            // set the owning side to null (unless already changed)
            if ($bilanmensuel->getProjet() === $this) {
                $bilanmensuel->setProjet(null);
            }
        }

        return $this;
    }






}
