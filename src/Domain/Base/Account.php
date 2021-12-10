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

namespace App\Domain\Base;

use App\Entity\Base\User;
use App\Events\Base\AccountCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class Account
 *
 * @package App\Domain
 */
class Account
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

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
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $handle
     * @param string $password
     * @param string $locale
     * @param User|null $registrar
     * @return User
     */
    public function create(string $handle, string $password, string $locale, ?User $registrar = null): User
    {
        $user = new User();
        $user->setHandle($handle);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
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
