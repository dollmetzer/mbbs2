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

use App\Entity\Transition;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\WorkflowEntityInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class Transfer
 *
 * @package App\Workflow
 */
class Transfer
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * Transfer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @param int $transitionId
     * @throws TransferException
     */
    public function execute(WorkflowEntityInterface $entity, int $transitionId): void
    {
        $transitionsRepository = $this->entityManager->getRepository(Transition::class);
        $transition = $transitionsRepository->find($transitionId);
        if (empty($transition)) {
            throw new TransferException('workflow.exception.transition.notfound');
        }

        $this->checkAccess($transition);

        $this->checkToState($entity, $transition);

        $entity->setState($transition->getToState());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @param Transition $transition
     * @throws TransferException
     */
    private function checkToState(WorkflowEntityInterface $entity, Transition $transition): void
    {
        if($entity->getState() !== $transition->getFromState()) {
            throw new TransferException('workflow.exception.transition.illegal');
        }
    }

    /**
     * @param Transition $transition
     * @throws TransferException
     */
    private function checkAccess(Transition $transition): void
    {
        $userRoles = $this->security->getUser()->getRoles();

        foreach($transition->getRoles() as $role) {
            if (in_array($role->getName(), $userRoles))
            {
                return;
            }
        }
        throw new TransferException('workflow.exception.transition.forbidden');
    }
}