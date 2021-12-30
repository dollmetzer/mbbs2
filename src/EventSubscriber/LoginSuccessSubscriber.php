<?php

namespace App\EventSubscriber;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [LoginSuccessEvent::class => 'updateUser'];
    }

    public function updateUser(LoginSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();
        $user->setLastlogin(new DateTimeImmutable());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
