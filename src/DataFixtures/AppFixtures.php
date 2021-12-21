<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const ADMIN_PASSWORD = 'Admin2022!';

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // ROLE
        $roleAdmin = new Role();
        $roleAdmin->setIsProtected(true);
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setTimestamps();
        $manager->persist($roleAdmin);

        $roleOrga = new Role();
        $roleAdmin->setIsProtected(true);
        $roleOrga->setName('ROLE_ALLOWED_TO_SWITCH');
        $roleOrga->setTimestamps();
        $manager->persist($roleOrga);

        $roleOrga = new Role();
        $roleOrga->setName('ROLE_ORGA');
        $roleOrga->setTimestamps();
        $manager->persist($roleOrga);

        $roleModerator = new Role();
        $roleModerator->setName('ROLE_MODERATOR');
        $roleModerator->setTimestamps();
        $manager->persist($roleModerator);

        // User
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, self::ADMIN_PASSWORD));
        $user->setLocale('en');
        $user->addRole($roleAdmin);
        $manager->persist($user);

        // Profile
        $profile = new Profile();
        $profile->setOwner($user);
        $manager->persist($profile);

        $manager->flush();
    }
}
