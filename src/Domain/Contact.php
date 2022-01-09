<?php

namespace App\Domain;

use App\Entity\Contact as ContactEntity;
use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Contact
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(User $originator, User $friend): void
    {
        $repo = $this->entityManager->getRepository(Profile::class);
        $originatorProfile = $repo->findOneBy(['owner' => $originator]);
        $friendProfile = $repo->findOneBy(['owner' => $friend]);

        $contact = new \App\Entity\Contact();
        $contact->setOriginator($originatorProfile);
        $contact->setContact($friendProfile);
        $this->entityManager->persist($contact);
        $contact2 = new ContactEntity();
        $contact2->setOriginator($friendProfile);
        $contact2->setContact($originatorProfile);
        $this->entityManager->persist($contact2);
        $this->entityManager->flush();
    }
}
