<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class for encrypting and decrypting with the defuse library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */

class DefuseEncryptor implements EncryptorInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct($oDoctrineEncryptSubscriber)
    {
        $this->encryptionKey              = null;
        $this->oDoctrineEncryptSubscriber = $oDoctrineEncryptSubscriber;
        $this->storeInDir                 = $oDoctrineEncryptSubscriber->projectRoot;
        $this->fileName                   = '.' . (new \ReflectionClass($this))->getShortName() . '.key';
        $this->fullStorePath              = $this->storeInDir . $this->fileName;
        $this->fs                         = new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        return \Defuse\Crypto\Crypto::encryptWithPassword($data, $this->getKey()) . '<ENC>';
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data)
    {
        return \Defuse\Crypto\Crypto::decryptWithPassword($data, $this->getKey());
    }

    private function getKey()
    {
        if ($this->encryptionKey === null) {
            if ($this->fs->exists($this->fullStorePath)) {
                $this->encryptionKey = file_get_contents($this->fullStorePath);
            } else {
                $this->encryptionKey = $this->oDoctrineEncryptSubscriber->generateRandomString();
                $this->fs->dumpFile($this->fullStorePath, $this->encryptionKey);
            }
        }

        return $this->encryptionKey;
    }
}
