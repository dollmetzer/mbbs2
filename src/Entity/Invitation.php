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
class Invitation
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     */
    private string $code;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="originator_id", referencedColumnName="id")
     */
    private User $originator;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $expirationDateTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getOriginator(): User
    {
        return $this->originator;
    }

    public function setOriginator(User $originator): void
    {
        $this->originator = $originator;
    }

    public function getExpirationDateTime(): DateTimeImmutable
    {
        return $this->expirationDateTime;
    }

    public function setExpirationDateTime(DateTimeImmutable $expirationDateTime): void
    {
        $this->expirationDateTime = $expirationDateTime;
    }
}
