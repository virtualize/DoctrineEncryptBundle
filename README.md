# michaeldegroot/DoctrineEncryptBundle

This is an fork from the original bundle created by ambta which can be found here:
[ambta/DoctrineEncryptBundle](https://github.com/ambta/DoctrineEncryptBundle)

This bundle has updated security by not rolling it's own encryption and using verified standardized library's from the field.

ambta/DoctrineEncryptBundle is **not** secured, It uses old crypto functions and programming mistakes like supplying a IV in ECB mode (which does nothing)

### Using [Halite](https://github.com/paragonie/halite)

*All deps are already installed with this package*

```yml
// Config.yml
ambta_doctrine_encrypt:
    encryptor_class: Halite
```

### Using [Defuse](https://github.com/defuse/php-encryption)

*You will need to require Defuse yourself*

`composer require "defuse/php-encryption ^2.0"`

```yml
// Config.yml
ambta_doctrine_encrypt:
    encryptor_class: Defuse
```



### Secret key

Secret key is generated if there is no key found. This is automatically generated and stored in the folder defined in the configuration

```yml
// Config.yml
ambta_doctrine_encrypt:
    secret_directory_path: '%kernel.project_dir%'   # Default value
```

Filename example: `.DefuseEncryptor.key` or `.HaliteEncryptor.key`

**Do not forget to add these files to your .gitignore file, you do not want this on your repository!**

### Documentation

* [Installation](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/installation.md)
* [Requirements](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/installation.md#requirements)
* [Configuration](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/configuration.md)
* [Usage](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/usage.md)
* [Console commands](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/commands.md)
* [Custom encryption class](https://github.com/michaeldegroot/DoctrineEncryptBundle/blob/master/Resources/doc/custom_encryptor.md)
