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

use App\Entity\Base\User;
use App\Entity\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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
     * @ManyToMany(targetEntity="Circle", mappedBy="contacts", cascade="persist")
     * @JoinTable(name="circle_2_contact")
     * @var ArrayCollection<Circle, Circle>
     */
    private $circles;

    public function __construct()
    {
        $this->circles = new ArrayCollection();
    }

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
     * @param User $owner
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

    /**
     * @return ArrayCollection<Circle, Circle>
     */
    public function getCircles(): Collection
    {
        return $this->circles;
    }

    /**
     * @param Circle $circle
     * @return $this
     */
    public function addCircle(Circle $circle): self
    {
        foreach($this->circles->getValues() as $associated) {
            if ($associated === $circle) return $this;
        }
        $this->circles->add($circle);
        return $this;
    }

    /**
     * @param Circle $circle
     * @return $this
     */
    public function removeCircle(Circle $circle): self
    {
        foreach($this->circles->getValues() as $associated) {
            if ($associated === $circle) {
                $this->circles->remove($circle);
            }
        }
        return $this;
    }
}