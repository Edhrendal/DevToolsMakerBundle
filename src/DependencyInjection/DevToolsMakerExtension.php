<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\DependencyInjection;

use Symfony\Component\Config\{
    Definition\Exception\InvalidConfigurationException,
    FileLocator
};
use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    Loader\PhpFileLoader
};
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\OptionsResolver\{
    Exception\ExceptionInterface,
    OptionsResolver
};

final class DevToolsMakerExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
       (
           new PhpFileLoader(
               $container,
               new FileLocator(__DIR__ . '/../Resources/config'),
               env: 'dev'
           )
       )
           ->load('commands.php');

       // Validate configuration values
        $this->resolveConfigurationRootNodeValues(
            $this->processConfiguration(
                configuration: $this->getConfiguration(),
                configs: $configs
            )
        );
    }

    /**
     * @param null|array<mixed> $config
     */
    public function getConfiguration(?array $config = null, ?ContainerBuilder $container = null): Configuration
    {
        // I don't like magic creation/loading
        return new Configuration();
    }

    /**
     * @param array<mixed> $processedConfigurationValues
     */
    private function resolveConfigurationRootNodeValues(array $processedConfigurationValues): void
    {
        $rootResolver = new OptionsResolver();
        $rootResolver
            ->define('root_namespace')
            ->required()
            ->allowedTypes('string');

        try {
            $rootResolver->resolve($processedConfigurationValues);
        } catch (ExceptionInterface $resolverException) {
            throw new InvalidConfigurationException(
                message: 'Configuration for "dev_tools_maker" is invalid.',
                previous: $resolverException
            );
        }
    }
}
