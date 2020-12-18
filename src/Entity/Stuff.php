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

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Item
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Stuff
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
     * @var boolean
     */
    private $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="Workflow", mappedBy="id")
     * @var Workflow
     */
    private $workflow;

    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="id")
     * @var State
     */
    private $state;

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
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
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
     * @return Workflow
     */
    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    /**
     * @param Workflow $workflow
     */
    public function setWorkflow(Workflow $workflow): void
    {
        $this->workflow = $workflow;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param State $state
     */
    public function setState(State $state): void
    {
        $this->state = $state;
    }

}