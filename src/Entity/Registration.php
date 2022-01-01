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

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Registration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $createdOn = null;

    /**
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private User $user;

    /**
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="registrar_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private ?User $registrar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function setCreatedOn(?DateTimeImmutable $createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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
     * @ORM\PreFlush
     */
    public function setTimestamp(): void
    {
        if (null === $this->createdOn) {
            $this->createdOn = new DateTimeImmutable();
        }
    }
}
