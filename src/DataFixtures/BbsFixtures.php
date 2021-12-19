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

use App\Entity\Base\User;
use App\Entity\Bbs\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BbsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userRepo = $manager->getRepository(User::class);
        $admin = $userRepo->findOneBy(['handle' => 'admin']);

        $profile = new Profile();
        $profile->setOwner($admin);
        $profile->setDisplayname('Administrator');
        $profile->setRealname('Chris Eisenhauer');
        $profile->setCity('Cloud City');
        $profile->setMotto('To serve and moderate');
        $profile->setTimestamps();
        $manager->persist($profile);

        $manager->flush();
    }
}
