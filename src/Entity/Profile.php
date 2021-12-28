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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Profile
{
    use Timestampable;

    public const ENUM_GENDER = [
        'n',    // none given
        'f',    // female
        'm',    // male
        'o',     // other
    ];

    public const ENUM_ZODIAC = [
        'n',            // none given
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
        'Pisces',        // Fische
    ];

    public const HTML_ZODIAC = [
        'n' => 32,
        'Aries' => '9800',
        'Taurus' => '9801',
        'Gemini' => '9802',
        'Cancer' => '9803',
        'Leo' => '9804',
        'Virgo' => '9805',
        'Libra' => '9806',
        'Scorpio' => '9807',
        'Saggitarius' => '9808',
        'Capricornus' => '9809',
        'Aquarius' => '9810',
        'Pisces' => '9811',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=32, nullable=false, options={"default":""})
     */
    private string $displayname = '';

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $picture = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $motto = null;

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"n"})
     * @Assert\Choice(choices=Profile::ENUM_GENDER, message="Choose a valid gender."))
     */
    private string $gender = 'n';

    /**
     * @ORM\Column(type="string", nullable=false, options={"default":"n"})
     * @Assert\Choice(callback="getZodiacs")
     */
    private string $zodiac = 'n';

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private ?string $country = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $city = null;

    /**
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private User $owner;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDisplayname(): string
    {
        return $this->displayname;
    }

    public function setDisplayname(string $displayname): void
    {
        $this->displayname = $displayname;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function getMotto(): ?string
    {
        return $this->motto;
    }

    public function setMotto(string $motto): void
    {
        $this->motto = $motto;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        if (!in_array($gender, self::ENUM_GENDER)) {
            throw new InvalidArgumentException('Invalid gender: '.$gender);
        }
        $this->gender = $gender;
    }

    public function getZodiac(): ?string
    {
        return $this->zodiac;
    }

    public function getZodiacSign(): string
    {
        if (null !== $this->zodiac) {
            return mb_chr(self::HTML_ZODIAC[$this->zodiac]);
        }

        return '&nbsp;';
    }

    public function getZodiacs(): array
    {
        return self::ENUM_ZODIAC;
    }

    public function setZodiac(string $zodiac): void
    {
        if (!in_array($zodiac, self::ENUM_ZODIAC)) {
            throw new InvalidArgumentException('Invalid zodiac: '.$zodiac);
        }
        $this->zodiac = $zodiac;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }
}
