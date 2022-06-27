<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\Command\Traits\Parameter;

use Edhrendal\DevToolsMakerBundle\{
    Exceptions\Autowire\SetterAlreadyCalledException,
    Exceptions\Autowire\SetterNotCalledException
};
use Symfony\Contracts\Service\Attribute\Required;

trait ProjectNamespaceParameterTrait
{
    private string $projectNamespaceParameter;

    #[Required]
    public function setProjectNamespace(string $projectNamespace): static
    {
        if (isset($this->projectNamespaceParameter)) {
            throw new SetterAlreadyCalledException(static::class, __METHOD__);
        }

        $this->projectNamespaceParameter = $projectNamespace;

        return $this;
    }

    protected function getProjectNamespace(): string
    {
        if (isset($this->projectNamespaceParameter) === false) {
            throw new SetterNotCalledException(static::class, 'setProjectNamespace');
        }

        return $this->projectNamespaceParameter;
    }
}
