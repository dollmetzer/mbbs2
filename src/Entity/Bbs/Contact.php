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

namespace App\Entity\Bbs;

use App\Entity\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class Contact
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Contact
{
    use Timestampable;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="App\Entity\Bbs\Profile", cascade="persist")
     * @JoinColumn(name="owner_uuid", referencedColumnName="uuid")
     * @var Profile
     */
    private $owner;

    /**
     * @ManyToOne(targetEntity="App\Entity\Bbs\Profile", cascade="persist")
     * @JoinColumn(name="contact_uuid", referencedColumnName="uuid")
     * @var Profile
     */
    private $contact;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Profile
     */
    public function getOwner(): Profile
    {
        return $this->owner;
    }

    /**
     * @param Profile $owner
     */
    public function setOwner(Profile $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Profile
     */
    public function getContact(): Profile
    {
        return $this->contact;
    }

    /**
     * @param Profile $contact
     */
    public function setContact(Profile $contact): void
    {
        $this->contact = $contact;
    }
}