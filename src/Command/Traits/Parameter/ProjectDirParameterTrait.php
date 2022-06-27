<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\Command\Traits\Parameter;

use Edhrendal\DevToolsMakerBundle\{
    Exceptions\Autowire\SetterAlreadyCalledException,
    Exceptions\Autowire\SetterNotCalledException
};
use Symfony\Contracts\Service\Attribute\Required;

trait ProjectDirParameterTrait
{
    private string $projectDirParameter;

    #[Required]
    public function setProjectDir(string $projectDir): static
    {
        if (isset($this->projectDirParameter)) {
            throw new SetterAlreadyCalledException(static::class, __METHOD__);
        }

        $this->projectDirParameter = $projectDir;

        return $this;
    }

    protected function getProjectDir(): string
    {
        if (isset($this->projectDirParameter) === false) {
            throw new SetterNotCalledException(static::class, 'setProjectDir');
        }

        return $this->projectDirParameter;
    }
}
