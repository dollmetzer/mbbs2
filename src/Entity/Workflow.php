<?php
/**
 * C O M P A R E   2   W O R K F L O W S
 * -------------------------------------
 * A small comparison of two workflow implementations
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Workflow
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Workflow
{
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16, options={"default":"state_machine"})
     * @var string
     */
    private $type = 'state_machine';

    /**
     * @ORM\Column(type="boolean", options={"default":"1"})
     * @var bool
     */
    private $auditTrail = true;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\State")
     * @ORM\JoinColumn(name="initialstate_id", referencedColumnName="id")
     * @var State
     */
    private $initialState;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\State", mappedBy="workflow", orphanRemoval=true, cascade={"persist", "remove"})
     * @var ArrayCollection<State, State>
     */
    private $states;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transition", mappedBy="workflow", orphanRemoval=true, cascade={"persist", "remove"})
     * @var ArrayCollection<Transition, Transition>
     */
    private $transitions;

    public function __construct()
    {
        $this->states = new ArrayCollection();
        $this->transitions = new ArrayCollection();
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isAuditTrail(): bool
    {
        return $this->auditTrail;
    }

    /**
     * @param bool $auditTrail
     */
    public function setAuditTrail(bool $auditTrail): void
    {
        $this->auditTrail = $auditTrail;
    }

    /**
     * @return State
     */
    public function getInitialState(): State
    {
        return $this->initialState;
    }

    /**
     * @param State $initialState
     */
    public function setInitialState(State $initialState): void
    {
        $this->initialState = $initialState;
    }

    /**
     * @return Collection
     */
    public function getStates(): Collection
    {
        return $this->states;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function addState(State $state): Workflow
    {
        foreach($this->states->getValues() as $associated) {
            if ($associated === $state) return $this;
        }
        $this->states->add($state);
        return $this;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function removeState(State $state): Workflow
    {
        foreach($this->states->getValues() as $associated) {
            if ($associated === $state) {
                $this->states->removeElement($state);
            }
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTransitions(): Collection
    {
        return $this->transitions;
    }

    /**
     * @param Transition $transition
     * @return $this
     */
    public function addTransition(Transition $transition): Workflow
    {
        foreach($this->transitions->getValues() as $associated) {
            if ($associated === $transition) return $this;
        }
        $this->transitions->add($transition);
        return $this;
    }

    /**
     * @param Transition $transition
     * @return $this
     */
    public function removeTransition(Transition $transition): Workflow
    {
        foreach($this->transitions->getValues() as $associated) {
            if ($associated === $transition) {
                $this->transitions->removeElement($transition);
            }
        }
        return $this;
    }
}