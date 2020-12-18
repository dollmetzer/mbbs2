<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Workflow;

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
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Workflow", inversedBy="states")
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
     * @return Workflow
     */
    public function getWorkflow(): int
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