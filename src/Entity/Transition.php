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
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Class Transition
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class Transition
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
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(name="fromstate_id", referencedColumnName="id")
     * @var State
     */
    private $fromState;

    /**
     * @ORM\ManyToOne(targetEntity="State")
     * @ORM\JoinColumn(name="tostate_id", referencedColumnName="id")
     * @var State
     */
    private $toState;

    /**
     * Access allowed for Roles
     *
     * @ManyToMany(targetEntity="Role", inversedBy="transition")
     * @JoinTable(name="transition_2_role",
     *      joinColumns={@ORM\JoinColumn(name="transition_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     * @var ArrayCollection<Role, Role>
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workflow", inversedBy="transitions")
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id")
     * @var Workflow
     */
    private $workflow;

    /**
     * Transition constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * @return State
     */
    public function getFromState(): State
    {
        return $this->fromState;
    }

    /**
     * @param State $fromState
     */
    public function setFromState(State $fromState): void
    {
        $this->fromState = $fromState;
    }

    /**
     * @return State
     */
    public function getToState(): State
    {
        return $this->toState;
    }

    /**
     * @param State $toState
     */
    public function setToState(State $toState): void
    {
        $this->toState = $toState;
    }

    /**
     * @return Collection<Role, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role): self
    {
        foreach($this->roles->getValues() as $associated) {
            if ($associated === $role) return $this;
        }
        $this->roles->add($role);
        return $this;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function removeRole(Role $role): self
    {
        foreach($this->roles->getValues() as $associated) {
            if ($associated === $role) {
                $this->roles->removeElement($role);
            }
        }
        return $this;
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
}