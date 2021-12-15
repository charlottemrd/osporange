<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use LdapTools\Bundle\LdapToolsBundle\Security\User\LdapUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_user")
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
}