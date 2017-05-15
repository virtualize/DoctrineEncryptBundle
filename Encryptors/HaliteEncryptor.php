<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

use \ParagonIE\Halite\HiddenString;
use \ParagonIE\Halite\EncryptionKey;
use \ParagonIE\Halite\KeyFactory;
use \ParagonIE\Halite\Symmetric\Crypto;

/**
 * Class for encrypting and decrypting with the halite library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */

class HaliteEncryptor implements EncryptorInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct($oDoctrineEncryptSubscriber)
    {
        $this->encryptionKey = null;
        $this->storeInDir    = $oDoctrineEncryptSubscriber->projectRoot;
        $this->fileName      = '.' . (new \ReflectionClass($this))->getShortName() . '.key';
        $this->fullStorePath = $this->storeInDir . $this->fileName;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        return \ParagonIE\Halite\Symmetric\Crypto::encrypt(new HiddenString($data), $this->getKey()) . '<ENC>';
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data)
    {
        return \ParagonIE\Halite\Symmetric\Crypto::decrypt($data, $this->getKey());
    }

    private function getKey()
    {
        if ($this->encryptionKey === null) {
            try {
                $this->encryptionKey = \ParagonIE\Halite\KeyFactory::loadEncryptionKey($this->fullStorePath);
            } catch (\ParagonIE\Halite\Alerts\CannotPerformOperation $e) {
                $this->encryptionKey = KeyFactory::generateEncryptionKey();
                \ParagonIE\Halite\KeyFactory::save($this->encryptionKey, $this->fullStorePath);
            }
        }

        return $this->encryptionKey;
    }
}
