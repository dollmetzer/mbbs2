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

namespace App\Entity\Bbs;

use App\Entity\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class Contact.
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Contact
{
    use Timestampable;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="App\Entity\Bbs\Profile")
     * @JoinColumn(name="ownerprofile_id", referencedColumnName="id")
     *
     * @var Profile
     */
    private $ownerProfile;

    /**
     * @ManyToOne(targetEntity="App\Entity\Bbs\Profile")
     * @JoinColumn(name="contactprofile_id", referencedColumnName="id")
     *
     * @var Profile
     */
    private $contactProfile;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOwnerProfile(): Profile
    {
        return $this->ownerProfile;
    }

    public function setOwnerProfile(Profile $ownerProfile): void
    {
        $this->ownerProfile = $ownerProfile;
    }

    public function getContactProfile(): Profile
    {
        return $this->contactProfile;
    }

    public function setContactProfile(Profile $contactProfile): void
    {
        $this->contactProfile = $contactProfile;
    }
}
