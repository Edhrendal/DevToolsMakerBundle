<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DevToolsMakerBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
