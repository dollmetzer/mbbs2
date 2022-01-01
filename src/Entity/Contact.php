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

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Contact
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ManyToOne(targetEntity="App\Entity\Profile")
     * @JoinColumn(name="originator_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Profile $originator;

    /**
     * @ManyToOne(targetEntity="App\Entity\Profile")
     * @JoinColumn(name="contact_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Profile $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginator(): Profile
    {
        return $this->originator;
    }

    public function setOriginator(Profile $originator): void
    {
        $this->originator = $originator;
    }

    public function getContact(): Profile
    {
        return $this->contact;
    }

    public function setContact(Profile $contact): void
    {
        $this->contact = $contact;
    }
}
