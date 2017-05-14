# michaeldegroot/DoctrineEncryptBundle

This is an fork from the original bundle created by ambta which can be found here:
[ambta/DoctrineEncryptBundle](https://github.com/ambta/DoctrineEncryptBundle)

This bundle has updated security by not rolling it's own encryption and using verified standardized library's from the field.
CBC mode is not secured, which is what ambta/DoctrineEncryptBundle is using.

### Important!
Set this in your config for secured encryption!
```yml
ambta_doctrine_encrypt:
    secret_key:           secret key not so secret
    encryptor:            DefuseEncryptor
    encryptor_class:      \Ambta\DoctrineEncryptBundle\Encryptors\DefuseEncryptor
```

### Documentation

* [Installation](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/installation.md)
* [Configuration](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/configuration.md)
* [Usage](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/usage.md)
* [Console commands](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/commands.md)
* [Custom encryption class](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/custom_encryptor.md)
