<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
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

        $manager->flush();
    }
}
