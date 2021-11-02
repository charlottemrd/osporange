<?php

namespace App\Entity;

use App\Repository\InfobilanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InfobilanRepository::class)
 */
class Infobilan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreprofit;

    /**
     *
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="infobilans")
     */
    private $profil;

    /**
     * @ORM\ManyToOne(targetEntity=BilanMensuel::class, inversedBy="infobilans")
     */
    private $bilanmensuel;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreprofit(): ?int
    {
        return $this->nombreprofit;
    }

    public function setNombreprofit(?int $nombreprofit): self
    {
        $this->nombreprofit = $nombreprofit;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getBilanmensuel(): ?BilanMensuel
    {
        return $this->bilanmensuel;
    }

    public function setBilanmensuel(?BilanMensuel $bilanmensuel): self
    {
        $this->bilanmensuel = $bilanmensuel;

        return $this;
    }


}
