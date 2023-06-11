<?php

namespace CQRS\Common\Infrastructure\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'mongodb:database:backup', aliases: ['m:d:b'], hidden: false)]
class BackupMongoDbCommand extends Command
{
    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Backup all collections in MongoDB database into a file.')
            ->setHelp('This command allows you to backup all collections in a MongoDB database into a file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $_ENV['BACKUP_FILE_NAME'];
        $outputFile = sprintf('%s/%s/%s', $this->projectDir, $_ENV['BACKUP_PATH'], $fileName);
        $process = Process::fromShellCommandline('make backup-db');
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('<error>An error occurred while compressing MongoDB collections.</error>');
            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info>MongoDB backup successfully created into file: %s </info>', $outputFile));
        return Command::SUCCESS;
    }
}