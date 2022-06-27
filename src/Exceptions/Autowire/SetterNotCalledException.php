<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\Exceptions\Autowire;

final class SetterNotCalledException extends \Exception implements ExceptionInterface
{
    public function __construct(string $class, string $method)
    {
        parent::__construct(
            message: "Setter {$class}::{$method}() has never been called."
        );
    }
}
