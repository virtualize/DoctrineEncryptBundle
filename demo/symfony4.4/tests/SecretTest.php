<?php

namespace App\Tests;

use App\Entity\Secret;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SecretTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel([]);
    }

    /**
     * @covers Secret::getSecret
     * @covers Secret::getName
     */
    public function testSecretsAreEncryptedInDatabase()
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get('doctrine.orm.entity_manager');

        // Make sure we do not store testdata
        $entityManager->beginTransaction();

        $name = 'test123';
        $secretString = 'i am a secret string';

        // Create entity to test with
        $secret = (new Secret())
            ->setName($name)
            ->setSecret($secretString);

        $entityManager->persist($secret);
        $entityManager->flush();

        // Fetch the actual data
        $secretRepository = $entityManager->getRepository(Secret::class);
        $qb = $secretRepository->createQueryBuilder('s');
        $qb->select('s')
            ->addSelect('(s.secret) as rawSecret')
            ->where('s.name = :name')
            ->setParameter('name',$name)
            ->orderBy('s.name','ASC');
        $result = $qb->getQuery()->getSingleResult();

        $actualSecretObject = $result[0];
        $actualRawSecret = $result['rawSecret'];
        
        self::assertEquals($secret->getSecret(), $actualSecretObject->getSecret());
        self::assertEquals($secret->getName(), $actualSecretObject->getName());
        // Make sure it is encrypted
        self::assertNotEquals($secret->getSecret(),$actualRawSecret);
    }
}
