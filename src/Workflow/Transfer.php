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
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StateChangeHelper
     */
    private $stateChangeHelper;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Transfer constructor.
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     * @param StateChangeHelper $stateChangeHelper
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        StateChangeHelper $stateChangeHelper,
        SessionInterface $session,
        TranslatorInterface $translator,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->logger = $logger;
        $this->stateChangeHelper = $stateChangeHelper;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function enterState(WorkflowEntityInterface $entity): void
    {
        $onEnter = $entity->getState()->getOnEnter();
        if (empty($onEnter)) {
            return;
        }

        $eventClassName = $this->stateChangeHelper->getClassName($onEnter);
        $eventMethodName = $this->stateChangeHelper->getMethodName($onEnter, 'onEnter');
        $eventClass = new $eventClassName($this->logger, $this->session, $this->translator);
        if (false === $eventClass->$eventMethodName($entity)) {
            throw new TransferException('workflow.exception.transition.onEnter.failed');
        }
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
        $this->checkState($entity, $transition);
        $this->checkLeaveEvent($entity, $transition);

        $entity->setState($transition->getToState());
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @param Transition $transition
     * @throws TransferException
     */
    private function checkState(WorkflowEntityInterface $entity, Transition $transition): void
    {
        if($entity->getState() !== $transition->getFromState()) {
            throw new TransferException('workflow.exception.transition.illegal');
        }
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @param Transition $transition
     * @throws Exception
     */
    private function checkLeaveEvent(WorkflowEntityInterface $entity, Transition $transition): void
    {
        $oldState = $transition->getFromState();
        $onLeave = $oldState->getOnLeave();
        if (empty($onLeave)) {
            return;
        }

        $eventClassName = $this->stateChangeHelper->getClassName($onLeave);
        $eventMethodName = $this->stateChangeHelper->getMethodName($onLeave, 'onLeave');
        $eventClass = new $eventClassName($this->logger, $this->session, $this->translator);
        if (false === $eventClass->$eventMethodName($entity)) {
            throw new TransferException('workflow.exception.transition.onleave.failed');
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