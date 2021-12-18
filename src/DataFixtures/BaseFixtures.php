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

use App\Entity\Base\Role;
use App\Entity\Base\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class AppFixtures.
 */
class BaseFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
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
        $user->setHandle('admin');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'Admin2020!'
        ));
        $user->setLocale('en');
        $user->setTimestamps();
        $user->addRole($roleAdmin);
        $user->setIsActive(true);
        $manager->persist($user);

        $manager->flush();
    }
}
