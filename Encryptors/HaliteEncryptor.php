<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

use \ParagonIE\Halite\{
    Asymmetric\Crypto as Asymmetric,
    HiddenString,
    EncryptionKey,
    KeyFactory
};
use ParagonIE\Halite\Symmetric\Crypto;

/**
 * Class for encrypting and decrypting with the halite library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */

class HaliteEncryptor implements EncryptorInterface {
    /**
     * @var string
     */
    private $secretKey;

    /**
     * {@inheritdoc}
     */
    public function __construct($secret) {
        $this->secret     = $secret;

        // Root dir
        $this->storeInDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    private function getKey() {
        try {
            $encryptionKey = \ParagonIE\Halite\KeyFactory::loadEncryptionKey($this->storeInDir . '/databaseEncryption.key');
        } catch (\ParagonIE\Halite\Alerts\CannotPerformOperation $e) {
            $encryptionKey = KeyFactory::deriveEncryptionKey(new HiddenString($this->secret), random_bytes(16));
           \ParagonIE\Halite\KeyFactory::save($encryptionKey, $this->storeInDir . '/databaseEncryption.key');
        }

        return $encryptionKey;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data) {
        return \ParagonIE\Halite\Symmetric\Crypto::encrypt(
            new HiddenString($data),
            $this->getKey()
        ) . '<ENC>';
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data) {
        return \ParagonIE\Halite\Symmetric\Crypto::decrypt(
            $data,
            $this->getKey()
        );
    }
}
