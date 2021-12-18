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

namespace App\Domain\Base;

use App\Entity\Base\User;
use App\Events\Base\AccountCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class Account.
 */
class Account
{
    private UserPasswordHasherInterface $passwordHasher;

    private EntityManagerInterface $entityManager;

    private EventDispatcherInterface$eventDispatcher;

    private LoggerInterface $logger;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
        $this->passwordHasher = $passwordHasher;
    }

    public function create(string $handle, string $password, string $locale, ?User $registrar = null): User
    {
        $user = new User();
        $user->setHandle($handle);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setLocale($locale);
        $user->setRegistrar($registrar);
        $user->setIsActive(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->logger->info('Account created', ['handle' => $user->getHandle(), 'id' => $user->getId()]);

        $event = new AccountCreatedEvent($user);
        $this->eventDispatcher->dispatch($event, AccountCreatedEvent::NAME);

        return $user;
    }
}
