<?php
/**
 * M B B S 2   -   B u l l e t i n   B o a r d   S y s t e m
 * ---------------------------------------------------------
 * A small BBS package for mobile use
 *
 * @author Dirk Ollmetzer <dirk.ollmetzer@ollmetzer.com>
 * @copyright (c) 2014-2020, Dirk Ollmetzer
 * @license GNU GENERAL PUBLIC LICENSE Version 3
 */

namespace App\EventSubscriber;

use App\Domain\Bbs\Contact;
use App\Entity\Bbs\Profile;
use App\Events\Base\AccountCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AccountSubscriber
 *
 * @package App\EventSubscriber
 */
class AccountCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [AccountCreatedEvent::NAME => 'onAccountCreatedEvent'];
    }

    /**
     * @param AccountCreatedEvent $event
     */
    public function onAccountCreatedEvent(AccountCreatedEvent $event): void
    {
        $user = $event->getUser();
        $registrar = $user->getRegistrar();

        $uuid = Uuid::uuid4();
        $profile = new Profile($uuid);
        $profile->setOwner($user);
        $profile->setDisplayname($user->getHandle());
        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        if(null !== $registrar) {
            $contact = new Contact($this->entityManager, $this->eventDispatcher, $this->logger);
            $contact->add($user, $registrar);
            $contact->add($registrar, $user);
        }

        $this->logger->info('AccountSubscriber -> account created for ' . $event->getUser()->getHandle());
    }
}