#Configuration Reference

There are 3 paramaters in the configuration of the Doctrine encryption bundle which are all optional.

* **secret_key** - The key used to encrypt the data (256 bit)
    * 32 character long string
    * Default: empty, the bundle will use your Symfony2 secret key.

* **encryptor_class** - Custom class for encrypting data
    * Encryptor class, [your own encryptor class](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/custom_encryptor.md) will override encryptor paramater
    * Default: empty

## yaml

``` yaml
ambta_doctrine_encrypt:
    secret_key:           AB1CD2EF3GH4IJ5KL6MN7OP8QR9ST0UW # Your own random key, optional for HaliteEncryptor
    encryptor_class:      \Ambta\DoctrineEncryptBundle\Encryptors\HaliteEncryptor # or DefuseEncryptor
```

## Usage

Read how to use the database encryption bundle in your project.
#### [Usage](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/usage.md)
