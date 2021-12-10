<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait Timestampable
 *
 * @package App\Entity
 */
trait Timestampable
{
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdOn;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedOn;

    /**
     * @ORM\PreFlush
     */
    public function setTimestamps(): void
    {
        if (null === $this->createdOn) {
            $this->createdOn = new DateTimeImmutable();
        }
        $this->updatedOn = new DateTimeImmutable();
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedOn(): ?DateTimeImmutable
    {
        return $this->createdOn;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedOn(): ?DateTimeImmutable
    {
        return $this->updatedOn;
    }
}
