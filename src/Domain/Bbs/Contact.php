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

namespace App\Domain\Bbs;

use App\Entity\Bbs\Circle;
use App\Entity\Bbs\Contact as ContactEntity;
use App\Entity\Base\User;
use App\Events\Bbs\ContactAddedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class Contact
 *
 * @package App\Domain
 */
class Contact
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param User $owner
     * @param User $user
     * @return ContactEntity
     */
    public function add(User $owner, User $user): ContactEntity
    {
        $contact = new ContactEntity();
        $contact->setOwner($owner);
        $contact->setContact($user);
        $contact->setTimestamps();
        $this->entityManager->persist($contact);

        $circleRepo = $this->entityManager->getRepository(Circle::class);
        $circle = $circleRepo->findOneBy(['owner' => $owner->getId(), 'isPrimary' => 1]);
        $circle->addContact($contact);
        $this->entityManager->persist($circle);

        $this->entityManager->flush();

        $this->logger->info('Added contact', ['owner' => $owner->getHandle(), 'contact' => $user->getHandle()]);

        $event = new ContactAddedEvent($contact);
        $this->eventDispatcher->dispatch($event, ContactAddedEvent::NAME);

        return $contact;
    }
}