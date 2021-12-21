<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use.
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2022, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\EventSubscriber;

use App\Entity\Profile;
use App\Events\AccountCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountCreatedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    private TranslatorInterface $translator;

    private EventDispatcherInterface $eventDispatcher;

    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [AccountCreatedEvent::NAME => 'onAccountCreatedEvent'];
    }

    public function onAccountCreatedEvent(AccountCreatedEvent $event): void
    {
        $user = $event->getUser();
        $registrar = $user->getRegistrar();

        $profile = new Profile();
        $profile->setOwner($user);
        $profile->setDisplayname($user->getUserIdentifier());
        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        if (null !== $registrar) {
            // todo: Add registrar as contact
        }
    }
}
