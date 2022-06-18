<?php

declare(strict_types=1);

namespace Edhrendal\DevToolsMakerBundle\Command;

use Symfony\Component\Console\{
    Attribute\AsCommand,
    Command\Command,
    Input\InputArgument,
    Input\InputInterface,
    Input\InputOption,
    Output\OutputInterface,
    Style\SymfonyStyle
};

#[AsCommand(
    name: 'make:autowire',
    description: 'Creates the "AutowireTrait"'
)]
final class MakeAutowireCommand extends Command
{
    private const ARGUMENT_FQCN = 'fqcn';
    private const OPTION_ACCESSOR = 'getter-name';

    protected function configure(): void
    {
        $this
            ->setHelp(
                <<<END
The <info>%command.name%</info> command generates the trait of autowiring.

<info>php %command.full_name% "Edhrendal\DevToolsMakerBundle\Command\MakeAutowireCommand"</info>

If the "AutowireTrait" already exists, nothing will be done.

You can specify the accessor method name if the generated one does not comply with your standards:

<info>php %command.full_name% --getter-name getService</info>
END
            )
            ->addArgument(
                static::ARGUMENT_FQCN,
                InputArgument::REQUIRED,
                'FQCN of the class to autowire.'
            )
            ->addOption(
                static::OPTION_ACCESSOR,
                'g',
                InputOption::VALUE_OPTIONAL,
                'Defines the getter name instead of the generated one.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->warning('Work in progress...');

        return static::SUCCESS;
    }
}
