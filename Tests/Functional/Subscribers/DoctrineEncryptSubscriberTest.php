<?php


namespace Ambta\DoctrineEncryptBundle\Tests\Functional\Subscribers;


use Ambta\DoctrineEncryptBundle\Encryptors\HaliteEncryptor;
use Ambta\DoctrineEncryptBundle\Subscribers\DoctrineEncryptSubscriber;
use Ambta\DoctrineEncryptBundle\Tests\Functional\Subscribers\Entity\CascadeTarget;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use Ambta\DoctrineEncryptBundle\Tests\Functional\Subscribers\Entity\Owner;

/**
 * @property EntityManager entityManager
 * @property false|string dbFile
 * @property DoctrineEncryptSubscriber subscriber
 * @property HaliteEncryptor encryptor
 */
class DoctrineEncryptSubscriberTest extends TestCase
{


    public function setUp()
    {
        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode                 = true;
        $proxyDir                  = null;
        $cache                     = null;
        $useSimpleAnnotationReader = false;

        $config = Setup::createAnnotationMetadataConfiguration(
            array(__DIR__ . "/Entity"),
            $isDevMode,
            $proxyDir,
            $cache,
            $useSimpleAnnotationReader
        );

        // database configuration parameters
        $this->dbFile = tempnam(sys_get_temp_dir(), 'amb_db');
        $conn = array(
            'driver' => 'pdo_sqlite',
            'path'   => $this->dbFile,
        );

        // obtaining the entity manager
        $this->entityManager = EntityManager::create($conn, $config);

        $reader = new AnnotationReader();
        $keyfile = __DIR__.'/fixtures/halite.key';
        $this->encryptor = new HaliteEncryptor($keyfile);

        $this->subscriber = new DoctrineEncryptSubscriber($reader, $this->encryptor);
        $this->entityManager->getEventManager()->addEventSubscriber($this->subscriber);

        $schemaTool = new SchemaTool($this->entityManager);
        $classes    = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }

    public function testEncryptionHappensOnOnlyAnnotatedFields()
    {
        $secret    = "It's a secret";
        $notSecret = "You're all welcome to know this.";
        $em        = $this->entityManager;
        $owner     = new Owner();
        $owner->setSecret($secret);
        $owner->setNotSecret($notSecret);
        $em->persist($owner);
        $em->flush();
        $em->clear();
        unset($owner);

        $connection = $em->getConnection();
        $stmt       = $connection->prepare('SELECT * from owner WHERE id = ?');
        $owners     = $em->getRepository(Owner::class)->findAll();
        $this->assertCount(1, $owners);
        /** @var Owner $owner */
        $owner = $owners[0];
        $this->assertEquals($secret, $owner->getSecret());
        $this->assertEquals($notSecret, $owner->getNotSecret());
        $stmt->bindValue(1, $owner->getId());
        $stmt->execute();
        $results = $stmt->fetchAll();
        $this->assertCount(1, $results);
        $result = $results[0];
        $this->assertEquals($notSecret, $result['notSecret']);
        $this->assertNotEquals($secret, $result['secret']);
        $this->assertStringEndsWith('<ENC>', $result['secret']);
        $decrypted = $this->encryptor->decrypt(str_replace('<ENC>', '', $result['secret']));
        $this->assertEquals($secret, $decrypted);
    }

    public function testEncryptionCascades()
    {
        $secret        = "It's a secret";
        $notSecret     = "You're all welcome to know this.";
        $em            = $this->entityManager;
        $owner         = new Owner();
        $em->persist($owner); // persist cascades
        $em->flush();

        $cascadeTarget = new CascadeTarget();
        $cascadeTarget->setSecret($secret);
        $cascadeTarget->setNotSecret($notSecret);
        $owner->setCascaded($cascadeTarget);
        $em->flush();
        $em->clear();
        unset($owner);
        unset($cascadeTarget);

        $connection     = $em->getConnection();
        $stmt           = $connection->prepare('SELECT * from cascadeTarget WHERE id = ?');
        $cascadeTargets = $em->getRepository(CascadeTarget::class)->findAll();
        $this->assertCount(1, $cascadeTargets);
        /** @var CascadeTarget $cascadeTarget */
        $cascadeTarget = $cascadeTargets[0];
        $this->assertEquals($secret, $cascadeTarget->getSecret());
        $this->assertEquals($notSecret, $cascadeTarget->getNotSecret());
        $stmt->bindValue(1, $cascadeTarget->getId());
        $stmt->execute();
        $results = $stmt->fetchAll();
        $this->assertCount(1, $results);
        $result = $results[0];
        $this->assertEquals($notSecret, $result['notSecret']);
        $this->assertNotEquals($secret, $result['secret']);
        $this->assertStringEndsWith('<ENC>', $result['secret']);
        $decrypted = $this->encryptor->decrypt(str_replace('<ENC>', '', $result['secret']));
        $this->assertEquals($secret, $decrypted);
    }

    public function tearDown()
    {
        unlink($this->dbFile);
    }
}