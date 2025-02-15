<?php

namespace Ambta\DoctrineEncryptBundle\DependencyInjection;

use Ambta\DoctrineEncryptBundle\Encryptors\DefuseEncryptor;
use Ambta\DoctrineEncryptBundle\Encryptors\HaliteEncryptor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Initialization of bundle.
 *
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DoctrineEncryptExtension extends Extension
{
    public const SupportedEncryptorClasses = array(
        'Defuse' => DefuseEncryptor::class,
        'Halite' => HaliteEncryptor::class,
    );

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Create configuration object
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // If empty encryptor class, use Halite encryptor
        if (array_key_exists($config['encryptor_class'], self::SupportedEncryptorClasses)) {
            $config['encryptor_class_full'] = self::SupportedEncryptorClasses[$config['encryptor_class']];
        } else {
            $config['encryptor_class_full'] = $config['encryptor_class'];
        }

        // Set parameters
        $container->setParameter('ambta_doctrine_encrypt.encryptor_class_name', $config['encryptor_class_full']);
        $container->setParameter('ambta_doctrine_encrypt.secret_directory_path',$config['secret_directory_path']);
        $container->setParameter('ambta_doctrine_encrypt.enable_secret_generation',$config['enable_secret_generation']);

        if (isset($config['secret'])) {
            $container->setParameter('ambta_doctrine_encrypt.secret',$config['secret']);
        }

        // Load service file
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        if (!isset($config['secret'])) {
            $loader->load('services_with_secretfactory.yml');
        } else {
            $loader->load('services_with_secret.yml');
        }

        // Remove usage of AttributeAnnotationReader when using php < 8.0
        if (PHP_VERSION_ID < 80000) {
            $container->setAlias('ambta_doctrine_annotation_reader','annotations.reader');
        }
    }

    /**
     * Get alias for configuration
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'ambta_doctrine_encrypt';
    }
}
