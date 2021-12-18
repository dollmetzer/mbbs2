<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Entity\Base;

use App\Entity\Timestampable;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * Encode a password manually:
 *   php bin/console security:encode-password
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     *
     * @var bool
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     *
     * @var string
     */
    private $handle;

    /**
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=2, options={"default":"en"})
     *
     * @var string
     */
    private $locale;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @var DateTimeImmutable
     */
    private $lastlogin;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="registrar_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     *
     * @var User
     */
    private $registrar;

    /**
     * @ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="user_2_role")
     *
     * @var ArrayCollection
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    /**
     * @return $this
     */
    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLastlogin(): ?DateTimeImmutable
    {
        return $this->lastlogin;
    }

    public function setLastlogin(DateTimeImmutable $lastlogin): void
    {
        $this->lastlogin = $lastlogin;
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
        foreach ($this->roles->getValues() as $role) {
            $roles[] = $role->getName();
        }

        return $roles;
    }

    public function getRawRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @return $this
     */
    public function addRole(Role $role): self
    {
        foreach ($this->roles->getValues() as $associated) {
            if ($associated === $role) {
                return $this;
            }
        }
        $this->roles->add($role);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeRole(Role $role): self
    {
        foreach ($this->roles->getValues() as $associated) {
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
     * @return $this
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRegistrar(): ?User
    {
        return $this->registrar;
    }

    public function setRegistrar(?User $registrar): void
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
