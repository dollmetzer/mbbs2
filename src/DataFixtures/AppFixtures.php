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

        // Admin User
        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, self::ADMIN_PASSWORD));
        $adminUser->setLocale('en');
        $adminUser->addRole($roleAdmin);
        $manager->persist($adminUser);

        // Profile
        $profile = new Profile();
        $profile->setOwner($adminUser);
        $profile->setDisplayname('Administrator');
        $manager->persist($profile);

        $manager->flush();
    }
}
