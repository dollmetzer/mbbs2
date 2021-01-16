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

namespace App\Domain;

use App\Entity\Circle;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function add(User $owner, User $user)
    {
        $circleRepo = $this->entityManager->getRepository(Circle::class);
        $circle = $circleRepo->findOneBy(['owner' => $owner, 'isPrimary' => true]);

        $contact = new \App\Entity\Contact();
        $contact->setOwner($owner);
        $contact->setContact($user);
        if ($circle) {
            $contact->addCircle($circle);
        }
        $contact->setTimestamps();
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $this->logger->info('Added contact', ['owner' => $owner->getHandle(), 'contact' => $user->getHandle()]);
    }
}