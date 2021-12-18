<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Entity\Bbs;

use App\Entity\Base\User;
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
     * @var User
     */
    private $ownerProfile;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOwnerProfile(): User
    {
        return $this->ownerProfile;
    }

    public function setOwnerProfile(User $ownerProfile): void
    {
        $this->ownerProfile = $ownerProfile;
    }
}
