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

/**
 * Class AppFixtures
 *
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
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
        $user->setPassword($this->passwordEncoder->encodePassword(
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
