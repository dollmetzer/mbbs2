<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setIsProtected(true);
        $roleAdmin->setName('ROLE_ADMIN');
        $manager->persist($roleAdmin);

        $roleModerator = new Role();
        $roleModerator->setName('ROLE_MODERATOR');
        $manager->persist($roleModerator);

        $roleOrga = new Role();
        $roleOrga->setName('ROLE_ORGA');
        $manager->persist($roleOrga);

        $user = new User();
        $user->setHandle('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Admin2020!'
        ));
        $user->addRole($roleAdmin);
        $manager->persist($user);

        $manager->flush();
    }
}
