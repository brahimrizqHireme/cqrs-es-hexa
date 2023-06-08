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

        $command = sprintf(
            'docker exec cqrs-mongodb mongodump --uri "%s" --archive=/var/backups/%s --gzip',
            $_ENV['MONGODB_URL'],
            $fileName
        );

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('<error>An error occurred while compressing MongoDB collections.</error>');
            return Command::FAILURE;
        }

        $command = sprintf('docker cp cqrs-mongodb:/var/backups/%s %s', $fileName, $outputFile);
        $process = Process::fromShellCommandline($command);
        $process->run();

        dd($process->getErrorOutput());
        if (!$process->isSuccessful()) {
            $output->writeln('<error>An error occurred while coping MongoDB collections.</error>');
            return Command::FAILURE;
        }

        shell_exec(sprintf('chmod -Rf 777 %s/data/db/*', $_SERVER['APP_HOME']));
        $output->writeln(sprintf('<info>MongoDB backup successfully created into file: %s </info>', $outputFile));
        return Command::SUCCESS;
    }
}