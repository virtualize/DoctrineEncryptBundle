<?php

namespace Ambta\DoctrineEncryptBundle\Encryptors;

/**
 * Class for encrypting and decrypting with the defuse library
 *
 * @author Michael de Groot <specamps@gmail.com>
 */

class SecureEncryptor implements EncryptorInterface {
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
        return \Defuse\Crypto\Crypto::Encrypt($data, $this->secret);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($data) {
        return \Defuse\Crypto\Crypto::Decrypt($data, $this->secret);
    }
}
