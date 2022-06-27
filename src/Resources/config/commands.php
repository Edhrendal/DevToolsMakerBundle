<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Edhrendal\DevToolsMakerBundle\Command\MakeAutowireCommand;

return static function (ContainerConfigurator $configurator): void {
    $makerServices = $configurator
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $makerServices
        ->set(MakeAutowireCommand::class)
        ->bind('$projectDir', '%kernel.project_dir%');
};
