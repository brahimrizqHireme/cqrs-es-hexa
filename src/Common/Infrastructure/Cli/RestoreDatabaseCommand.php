<?php

namespace CQRS\Common\Infrastructure\Cli;


use CQRS\Common\Domain\Enum\Database;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'mongodb:database:restore', aliases: ['m:d:r'], hidden: false)]
class RestoreDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Restores a MongoDB database from a file.')
            ->setHelp('This command allows you to restore a MongoDB database from a backup file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = Process::fromShellCommandline('make restore-db');
        $process->run();

        if ($process->isSuccessful()) {
            $output->writeln('<info> Database restore completed successfully. </info>');
        } else {
            $output->writeln('<error>Database restore failed.</error>');
        }

        return Command::SUCCESS;
    }
}