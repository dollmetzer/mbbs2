<?php


namespace App\Tests\unit\Entity\Bbs;

use App\Entity\Bbs\Profile;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;;
// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProfileTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    public function testCreateNewProfileWithoutUUID(): void
    {
        $entity = new Profile();
        $this->assertNull($entity->getUuid());
    }

    public function testPersistNewProfileWithoutUUID(): void
    {
        $entity = new Profile();
        $this->expectException(\Doctrine\ORM\ORMException::class);
        $this->em->persist($entity);
    }

    public function testCreateNewProfileWithUUID(): void
    {
        $uuid = Uuid::uuid4();
        $entity = new Profile($uuid);
        $this->em->persist($entity);
        $this->assertEquals($uuid, $entity->getUuid());
    }
}