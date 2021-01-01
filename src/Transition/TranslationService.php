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

namespace App\Transition;

use App\Entity\WorkflowEntityInterface;
use App\Workflow\StateChangingInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TranslationService
 *
 * @package App\Workflow
 */
class TranslationService implements StateChangingInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TranslationService constructor.
     *
     * @param LoggerInterface $logger
     * @param Session $session
     * @param TranslatorInterface $translator
     */
    public function __construct(LoggerInterface $logger, Session $session, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @return string[]
     */
    public function getEventMethods(): array
    {
        return [
            'onEnter',
            'onLeave',
            'sendOriginalTexts',
            'fetchTranslations'
        ];
    }

    /**
     * @inheritDoc
     */
    public function onEnter(WorkflowEntityInterface $entity): bool
    {
        $this->logger->info('TranslationService: called onEnter');
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onLeave(WorkflowEntityInterface $entity): bool
    {
        $this->logger->info('TranslationService: called onLeave');
        return true;
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @return bool
     */
    public function sendOriginalTexts(WorkflowEntityInterface $entity): bool
    {
        $message = $this->translator->trans('workflow.message.translation.sent');
        $this->session->getFlashBag()->add('info', $message);
        $this->logger->info('TranslationService: Send original texts to translation service');
        return true;
    }

    /**
     * @param WorkflowEntityInterface $entity
     * @return bool
     */
    public function fetchTranslations(WorkflowEntityInterface $entity): bool
    {
        $message = $this->translator->trans('workflow.message.translation.fetched');
        $this->session->getFlashBag()->add('info', $message);
        $this->logger->info('TranslationService: Fetch Translations from translation service');
        return true;
    }
}