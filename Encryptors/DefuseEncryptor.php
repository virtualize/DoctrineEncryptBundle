<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

/**
 * Class for encrypting and decrypting with the defuse library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */

class DefuseEncryptor implements EncryptorInterface {
    /**
     * @var string
     */
    private $secretKey;

    /**
     * {@inheritdoc}
     */
    public function __construct($secret) {
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data) {
        return \Defuse\Crypto\Crypto::encryptWithPassword($data, $this->secret) . '<ENC>';
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data) {
        return \Defuse\Crypto\Crypto::decryptWithPassword($data, $this->secret);
    }
}
