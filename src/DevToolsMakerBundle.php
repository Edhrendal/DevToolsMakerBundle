<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle;

use Edhrendal\DevToolsMakerBundle\DependencyInjection\DevToolsMakerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DevToolsMakerBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        // I don't like magic creation/loading
        return new DevToolsMakerExtension();
    }
}
