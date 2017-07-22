<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{
    InputArgument, InputInterface
};
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class ExtractCommand extends Command
{
    const COMMAND_NAME = 'extract';
    const FILE_ARGUMENT = 'compressed-file';
    const DIRECTORY_ARGUMENT = 'directory-name';
    const LOCATION_OPTION = 'location';
    const LOCATION_OPTION_SHORTCUT = 'l';

    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription('Extracts a zip file into a requested directory.')
            ->addArgument(static::FILE_ARGUMENT, InputArgument::REQUIRED, 'The file to be extracted. (full path)')
            ->addArgument(static::DIRECTORY_ARGUMENT, InputArgument::REQUIRED, 'The name of the folder where the content is going to be held.')
            ->addOption(
                static::LOCATION_OPTION,
                static::LOCATION_OPTION_SHORTCUT,
                InputArgument::OPTIONAL,
                'The final location, where the zip is going to be extracted to. (Default=cwd)',
                getcwd()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $directory = $input->getOption(static::LOCATION_OPTION) . '/' . $input->getArgument(static::DIRECTORY_ARGUMENT);
        $zipFile = $input->getArgument(static::FILE_ARGUMENT);

        $this->validateZipFileExistence($zipFile, $output);
        $this->validateDirectoryExistence($directory, $output);

        $this->extract($zipFile, $directory);

        $output->writeln('<info>File extracted successfully!.</info>');
    }

    private function validateZipFileExistence(string $file, OutputInterface $output): void
    {
        if (!is_file($file)) {
            $output->writeln('<error>File ' . $file . ' does not exists. </error>');

            exit(1);
        }
    }

    private function validateDirectoryExistence(string $directory, OutputInterface $output): void
    {
        if (is_dir($directory)) {
            $output->writeln('<error>Directory ' . $directory . ' already exists. </error>');

            exit(1);
        }
    }

    private function extract(string $zipFile, string $directory): ExtractCommand
    {
        $archive = new ZipArchive;

        $archive->open($zipFile);
        $archive->extractTo($directory);
        $archive->close();

        return $this;
    }
}