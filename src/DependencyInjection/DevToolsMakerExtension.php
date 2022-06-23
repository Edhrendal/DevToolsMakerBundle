<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    Loader\PhpFileLoader
};
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class DevToolsMakerExtension extends Extension
{
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
    }
}
