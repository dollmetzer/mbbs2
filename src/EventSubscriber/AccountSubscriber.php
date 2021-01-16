<?php


namespace App\EventSubscriber;

use App\Domain\Contact;
use App\Entity\Circle;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Events\AccountEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountSubscriber implements EventSubscriberInterface
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
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [AccountEvent::NAME => 'onAccountEvent'];
    }

    public function onAccountEvent(AccountEvent $event)
    {
        $user = $event->getUser();

        $circle = new Circle();
        $circle->setOwner($user);
        $circle->setName($this->translator->trans('bbs.text.newcontacts', [], 'messages', $user->getLocale()));
        $circle->setIsPrimary(true);
        $this->entityManager->persist($circle);
        $this->entityManager->flush();

        $userRepo = $this->entityManager->getRepository(User::class);
        $registrar = $user->getRegistrar();

        if(null !== $registrar) {
            $contact = new Contact($this->entityManager, $this->logger);
            $contact->add($user, $registrar);
            $contact->add($registrar, $user);
        }

        $this->logger->info('AccountSubscriber -> account created for ' . $event->getUser()->getHandle());
    }
}