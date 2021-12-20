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

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Role
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isProtected = false;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="roles")
     * @JoinTable(name="user_2_role")
     *
     * @var ArrayCollection
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isProtected(): bool
    {
        return $this->isProtected;
    }

    public function setIsProtected(bool $isProtected): void
    {
        $this->isProtected = $isProtected;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return $this
     */
    public function addUser(User $user): self
    {
        foreach ($this->users->getValues() as $associated) {
            if ($associated === $user) {
                return $this;
            }
        }
        $this->users->add($user);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeUser(User $user): self
    {
        foreach ($this->users->getValues() as $associated) {
            if ($associated === $user) {
                $this->users->removeElement($user);
            }
        }

        return $this;
    }
}
