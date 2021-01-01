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

namespace App\Workflow;

use App\Entity\WorkflowEntityInterface;

interface StateChangingInterface
{
    /**
     * @return array
     */
    public function getEventMethods(): array;

    /**
     * @param WorkflowEntityInterface $entity
     * @return bool
     */
    public function onEnter(WorkflowEntityInterface $entity): bool;

    /**
     * @param WorkflowEntityInterface $entity
     * @return bool
     */
    public function onLeave(WorkflowEntityInterface $entity): bool;
}