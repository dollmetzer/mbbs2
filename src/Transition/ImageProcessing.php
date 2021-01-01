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
 * Class ImageProcessing
 *
 * @package App\Workflow
 */
class ImageProcessing implements StateChangingInterface
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
     * ImageProcessing constructor.
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
            'onLeave'
        ];
    }

    /**
     * @inheritDoc
     */
    public function onEnter(WorkflowEntityInterface $entity): bool
    {
        $this->logger->info('Fetch processed pictures from image processing service');
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onLeave(WorkflowEntityInterface $entity): bool
    {
        $this->logger->info('Send original pictures to image processing service');
        return true;
    }
}