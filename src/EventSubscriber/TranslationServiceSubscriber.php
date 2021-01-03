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

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TranslationServiceSubscriber
 *
 * @package App\EventSubscriber
 */
class TranslationServiceSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TranslationServiceSubscriber constructor.
     * @param LoggerInterface $logger
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        LoggerInterface $logger,
        SessionInterface $session,
        TranslatorInterface $translator
    )
    {
        $this->logger = $logger;
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.foto_publishing.enter.QA' => 'onEnter',
            'workflow.foto_publishing.leave.Vorbereitung' => 'onLeave',
        ];
    }

    /**
     * @param Event $event
     */
    public function onEnter(Event $event): void
    {
        $message = $this->translator->trans('workflow.message.translation.fetched');
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->session;
        $session->getFlashBag()->add('info', $message);

        $this->logger->info(sprintf(
            'TranslationService item (id: "%s") performed onEnter transition "%s" from "%s" to "%s"',
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()->getTos())
        ));
    }

    /**
     * @param Event $event
     */
    public function onLeave(Event $event): void
    {
        $message = $this->translator->trans('workflow.message.translation.sent');
        /** @var \Symfony\Component\HttpFoundation\Session\Session $session */
        $session = $this->session;
        $session->getFlashBag()->add('info', $message);

        $this->logger->info(sprintf(
            'TranslationService item (id: "%s") performed on Leave transition "%s" from "%s" to "%s"',
            $event->getSubject()->getId(),
            $event->getTransition()->getName(),
            implode(', ', array_keys($event->getMarking()->getPlaces())),
            implode(', ', $event->getTransition()->getTos())
        ));
    }
}