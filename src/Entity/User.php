<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 */
class User implements LdapUserInterface, UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ldapGuid;

    /**
     * @ORM\Column(type="text")
     */
    private $username;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fullusername;

    /**
     * @ORM\OneToMany(targetEntity=Projet::class, mappedBy="userchef")
     */
    private $projets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ldapGuid
     *
     * @param string $ldapGuid
     *
     * @return AppUser
     */
    public function setLdapGuid($ldapGuid)
    {
        $this->ldapGuid = $ldapGuid;

        return $this;
    }

    /**
     * Get ldapGuid
     *
     * @return string
     */
    public function getLdapGuid()
    {
        return $this->ldapGuid;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return AppUser
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getFullusername(): ?string
    {
        return $this->fullusername;
    }

    public function setFullusername(?string $fullusername): self
    {
        $this->fullusername = $fullusername;

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
            $projet->setUserchef($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getUserchef() === $this) {
                $projet->setUserchef(null);
            }
        }

        return $this;
    }



    public function __toString()
    {
        return $this->getFullusername();
    }

}