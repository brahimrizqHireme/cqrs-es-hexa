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
    public function __construct(private readonly string $projectDir)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Restores a MongoDB database from a file.')
            ->setHelp('This command allows you to restore a MongoDB database from a backup file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $_ENV['BACKUP_FILE_NAME'];
        $outputFile = sprintf('%s/%s/%s', $this->projectDir, $_ENV['BACKUP_PATH'], $fileName);
        $command = sprintf('docker cp cqrs-mongodb:/backup/%s %s', $fileName, $outputFile);
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln(sprintf('<error>An error occurred while copying %s collections.</error>', $fileName));
            return Command::FAILURE;
        }

        $command = sprintf('docker exec cqrs-mongodb mongorestore --uri "%s" --drop --gzip --archive=/backup/%s --nsInclude="%s.*"',
            $_ENV['MONGODB_URL'],
            $fileName,
            $_ENV['MONGODB_DB']
        );

        $process = Process::fromShellCommandline($command);
        $process->run();

        if ($process->isSuccessful()) {
            $output->writeln('<info> Database restore completed successfully. </info>');
        } else {
            $output->writeln('<error>Database restore failed.</error>');
        }

        return Command::SUCCESS;
    }
}