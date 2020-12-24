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

use Doctrine\ORM\Mapping as ORM;

/**
 * Class State
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @package App\Entity
 */
class State
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
     * @ORM\Column(type="string", length=32, nullable=false)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @var string Class name and method name
     */
    private $onEnter;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @var string Class name and method name
     */
    private $onLeave;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Workflow", inversedBy="states")
     * @ORM\JoinColumn(name="workflow_id", referencedColumnName="id")
     * @var Workflow
     */
    private $workflow;

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
    public function getOnEnter(): ?string
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
    public function getOnLeave(): ?string
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

    /**
     * @return Workflow|null
     */
    public function getWorkflow(): ?Workflow
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