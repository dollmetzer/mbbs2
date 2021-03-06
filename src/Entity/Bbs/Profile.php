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
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Profile
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity\Bbs
 */
class Profile
{
    use Timestampable;

    const ENUM_GENDER = [
        'f',    // female
        'm',    // male
        'o'     // other
    ];

    const ENUM_ZODIAC = [
        'Aries',        // Widder
        'Taurus',       // Stier
        'Gemini',       // Zwilling
        'Cancer',       // Krebs
        'Leo',          // Löwe
        'Virgo',        // Jungfrau
        'Libra',        // Waage
        'Scorpio',      // Scorpion
        'Saggitarius',  // Schütze
        'Capricornus',  // Steinbock
        'Aquarius',     // Wassermann
        'Pisces'        // Fische
    ];

    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * Hint: No UUID generation strategy. Entity must bring the UUID from outside
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     * @var string
     */
    private $displayname;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @var string
     */
    private $realname;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @var string
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @var string
     */
    private $motto;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices=Profile::ENUM_GENDER, message="Choose a valid gender."))
     * @var string
     */
    private $gender;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(callback="getZodiacs")
     * @var string
     */
    private $zodiac;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @ManyToOne(targetEntity="App\Entity\Base\User")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     * @var User
     */
    private $owner;

    /**
     * Profile constructor.
     * @param UuidInterface|null $uuid
     */
    public function __construct(?UuidInterface $uuid = null)
    {
        if ($uuid) {
            $this->uuid = $uuid;
        }
    }

    /**
     * @return UuidInterface|null
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param UuidInterface $uuid
     */
    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getDisplayname(): string
    {
        return $this->displayname;
    }

    /**
     * @param string $displayname
     */
    public function setDisplayname(string $displayname): void
    {
        $this->displayname = $displayname;
    }

    /**
     * @return null|string
     */
    public function getRealname(): ?string
    {
        return $this->realname;
    }

    /**
     * @param string|null $realname
     */
    public function setRealname(?string $realname): void
    {
        $this->realname = $realname;
    }

    /**
     * @return string
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     */
    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @return null|string
     */
    public function getMotto(): ?string
    {
        return $this->motto;
    }

    /**
     * @param null|string $motto
     */
    public function setMotto(?string $motto): void
    {
        $this->motto = $motto;
    }

    /**
     * @return null|string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender): void
    {
        if (!in_array($gender, self::ENUM_GENDER)) {
            throw new InvalidArgumentException("Invalid gender: " . $gender);
        }
        $this->gender = $gender;
    }

    /**
     * @return null|string
     */
    public function getZodiac(): ?string
    {
        return $this->zodiac;
    }

    public function getZodiacs(): array
    {
        return self::ENUM_ZODIAC;
    }

    /**
     * @param string $zodiac
     */
    public function setZodiac(string $zodiac): void
    {
        if (!in_array($zodiac, self::ENUM_ZODIAC)) {
            throw new InvalidArgumentException("Invalid zodiac: " . $zodiac);
        }
        $this->zodiac = $zodiac;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
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
}