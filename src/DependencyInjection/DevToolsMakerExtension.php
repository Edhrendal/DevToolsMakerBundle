<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\DependencyInjection;

use Edhrendal\DevToolsMakerBundle\Command\MakeAutowireCommand;
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
        $resolvedConfigurationValues = $this->resolveConfigurationRootNodeValues(
            $this->processConfiguration(
                configuration: $this->getConfiguration(),
                configs: $configs
            )
        );

        // Dynamic configuration value injection
        $this->injectDynamicConfigurationValues($container, $resolvedConfigurationValues);
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
     * @return array{'root_namespace': string}
     */
    private function resolveConfigurationRootNodeValues(array $processedConfigurationValues): array
    {
        $rootResolver = new OptionsResolver();
        $rootResolver
            ->define('root_namespace')
            ->required()
            ->allowedTypes('string');

        try {
            return $rootResolver->resolve($processedConfigurationValues);
        } catch (ExceptionInterface $resolverException) {
            throw new InvalidConfigurationException(
                message: 'Configuration for "dev_tools_maker" is invalid.',
                previous: $resolverException
            );
        }
    }

    /**
     * @param array{'root_namespace': string} $resolvedConfigurationValues
     */
    private function injectDynamicConfigurationValues(
        ContainerBuilder $container,
        array $resolvedConfigurationValues
    ): void {
        $container
            ->getDefinition(MakeAutowireCommand::class)
            ->addMethodCall('setProjectNamespace', [$resolvedConfigurationValues['root_namespace']]);
    }
}
