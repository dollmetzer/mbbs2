<?php


namespace App\Entity;


use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

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