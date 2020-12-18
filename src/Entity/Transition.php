<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="State", mappedBy="id")
     * @var State
     */
    private $fromState;

    /**
     * @ORM\OneToMany(targetEntity="State", mappedBy="id")
     * @var State
     */
    private $toState;

    /**
     * Access allowed for Roles
     *
     * @ManyToMany(targetEntity="Role", inversedBy="users")
     * @JoinTable(name="transition_2_role")
     * @var ArrayCollection<Role, Role>
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workflow", inversedBy="states")
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id")
     * @var Workflow
     */
    private $workflow;

    /**
     * @var string Class name and method name
     */
    private $onEnter;

    /**
     * @var string Class name and method name
     */
    private $onLeave;

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
     * @return ArrayCollection
     */
    public function getRoles(): ArrayCollection
    {
        return $this->roles;
    }

    /**
     * @param ArrayCollection $roles
     */
    public function setRoles(ArrayCollection $roles): void
    {
        $this->roles = $roles;
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
     * @return string
     */
    public function getOnEnter(): string
    {
        return $this->onEnter;
    }

    /**
     * @param string $onEnter
     */
    public function setOnEnter(string $onEnter): void
    {
        $this->onEnter = $onEnter;
    }

    /**
     * @return string
     */
    public function getOnLeave(): string
    {
        return $this->onLeave;
    }

    /**
     * @param string $onLeave
     */
    public function setOnLeave(string $onLeave): void
    {
        $this->onLeave = $onLeave;
    }
}