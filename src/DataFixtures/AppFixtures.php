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
        // ROLE
        $roleAdmin = new Role();
        $roleAdmin->setIsProtected(true);
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setTimestamps();
        $manager->persist($roleAdmin);

        $roleModerator = new Role();
        $roleModerator->setName('ROLE_MODERATOR');
        $roleModerator->setTimestamps();
        $manager->persist($roleModerator);

        $roleOrga = new Role();
        $roleOrga->setName('ROLE_ORGA');
        $roleOrga->setTimestamps();
        $manager->persist($roleOrga);

        // User
        $user = new User();
        $user->setHandle('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Admin2020!'
        ));
        $user->setTimestamps();
        $user->addRole($roleAdmin);
        $manager->persist($user);

        $manager->flush();
    }
}
