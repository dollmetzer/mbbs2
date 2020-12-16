<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Item
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
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     * @var integer
     */
    private $active = true;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var
     */
    private $marking;

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
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     */
    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getMarking()
    {
        return $this->marking;
    }

    /**
     * @param mixed $marking
     */
    public function setMarking($marking): void
    {
        $this->marking = $marking;
    }

}