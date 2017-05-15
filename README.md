# michaeldegroot/DoctrineEncryptBundle

This is an fork from the original bundle created by ambta which can be found here:
[ambta/DoctrineEncryptBundle](https://github.com/ambta/DoctrineEncryptBundle)

This bundle has updated security by not rolling it's own encryption and using verified standardized library's from the field.

ambta/DoctrineEncryptBundle is **not** secured, It uses old crypto functions and programming mistakes like supplying a IV in ECB mode (which does nothing)

### Using [Defuse](https://github.com/defuse/php-encryption)

*All deps are already installed with this package*

```yml
// Config.yml
ambta_doctrine_encrypt:
    encryptor_class: Defuse
```

### Using [Halite](https://github.com/paragonie/halite)

*You will need to require Halite yourself*

`composer require "paragonie/halite 3.2"`

```yml
// Config.yml
ambta_doctrine_encrypt:
    encryptor_class: Halite
```

### Secret key

Secret key is generated if there is no key found. This is automatically generated and stored in your project root folder

Filename example: `.DefuseEncryptor.key` or `.HaliteEncryptor.key`

**Do not forget to add these files to your .gitignore file, you do not want this on your repository!**

### Documentation

* [Installation](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/installation.md)
* [Requirements](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/installation.md#requirements)
* [Configuration](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/configuration.md)
* [Usage](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/usage.md)
* [Console commands](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/commands.md)
* [Custom encryption class](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/custom_encryptor.md)
