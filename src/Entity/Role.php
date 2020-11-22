<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity()
 */
class Role
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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $isProtected=false;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="roles")
     * @JoinTable(name="user_2_role")
     * @var ArrayCollection|User[]
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
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
    public function isProtected(): bool
    {
        return $this->isProtected;
    }

    /**
     * @param bool $isProtected
     */
    public function setIsProtected(bool $isProtected): void
    {
        $this->isProtected = $isProtected;
    }

    /**
     * @see UserInterface
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        foreach($this->users->getValues() as $associated) {
            if ($associated === $user) return $this;
        }
        $this->users->add($user);
        return $this;
    }

    public function removeUser(User $user): self
    {
        foreach($this->users->getValues() as $associated) {
            if ($associated === $user) {
                $this->users->remove($user);
            }
        }
        return $this;
    }

}