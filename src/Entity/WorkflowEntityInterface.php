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

use App\Entity\State;
use App\Entity\Workflow;

/**
 * Interface WorkflowEntityInterface
 */
interface WorkflowEntityInterface
{
    /**
     * @return Workflow
     */
    public function getWorkflow(): Workflow;

    /**
     * @param Workflow $workflow
     */
    public function setWorkflow(Workflow $workflow): void;

    /**
     * @return State
     */
    public function getState(): State;

    /**
     * @param State $state
     */
    public function setState(State $state): void;
}