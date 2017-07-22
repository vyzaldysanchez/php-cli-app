<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{
    InputArgument, InputInterface, InputOption
};
use Symfony\Component\Console\Output\OutputInterface;

class GreetCommand extends Command
{
    public const HELLO_GREETING = 'Hello';

    protected function configure(): void
    {
        $this->setName('greet')
            ->setDescription('Displays a greeting using the name passed as argument.')
            ->addArgument('name', InputArgument::REQUIRED, 'Your name.')
            ->addOption('greeting', 'cgr', InputOption::VALUE_OPTIONAL, 'Overrides the default greeting.', self::HELLO_GREETING);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $message = sprintf('%s, %s', $input->getOption('greeting'), $input->getArgument('name'));

        $output->writeln("<info>{$message}</info>");
    }
}