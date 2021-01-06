<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * Encode a password manually:
 *   php bin/console security:encode-password
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class User implements UserInterface
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     * @var bool
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @var string
     */
    private $handle;

    /**
     * @ORM\Column(type="string")
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=2, options={"default":"en"})
     * @var string
     */
    private $locale;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="registrar_id", referencedColumnName="id")
     * @var User
     */
    private $registrar;

    /**
     * @ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="user_2_role")
     * @var ArrayCollection<Role, Role>
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string|null
     */
    public function getHandle(): ?string
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return $this
     */
    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->handle;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        foreach($this->roles->getValues() as $role) {
            $roles[] = $role->getName();
        }
        return $roles;
    }

    /**
     * @return Collection<Role, Role>
     */
    public function getRawRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role): self
    {
        foreach($this->roles->getValues() as $associated) {
            if ($associated === $role) return $this;
        }
        $this->roles->add($role);
        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function removeRole(Role $role): self
    {
        foreach($this->roles->getValues() as $associated) {
            if ($associated === $role) {
                $this->roles->removeElement($role);
            }
        }
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    /**
     * @param string|null $password
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return User
     */
    public function getRegistrar(): User
    {
        return $this->registrar;
    }

    /**
     * @param User $registrar
     */
    public function setRegistrar(User $registrar): void
    {
        $this->registrar = $registrar;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
