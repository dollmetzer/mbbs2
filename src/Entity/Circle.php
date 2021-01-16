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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class Circle
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Circle
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
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isPrimary=false;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     * @var User
     */
    private $owner;

    /**
     * @ManyToMany(targetEntity="Contact", inversedBy="circles")
     * @JoinTable(name="circle_2_contact")
     * @var ArrayCollection<Contact, Contact>
     */
    private $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary
     */
    public function setIsPrimary(bool $isPrimary): void
    {
        $this->isPrimary = $isPrimary;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return ArrayCollection<Contact, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @param Contact $contact
     * @return $this
     */
    public function addContact(Contact $contact): self
    {
        foreach($this->contacts->getValues() as $associated) {
            if ($associated === $contact) return $this;
        }
        $this->contacts->add($contact);
        return $this;
    }

    /**
     * @param Contact $contact
     * @return $this
     */
    public function removeContact(Contact $contact): self
    {
        foreach($this->contacts->getValues() as $associated) {
            if ($associated === $contact) {
                $this->contacts->remove($contact);
            }
        }
        return $this;
    }
}