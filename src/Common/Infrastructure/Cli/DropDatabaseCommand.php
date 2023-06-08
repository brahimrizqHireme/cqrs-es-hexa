<?php

namespace CQRS\Common\Infrastructure\Cli;

use CQRS\Common\Domain\Enum\Database;
use CQRS\Common\Infrastructure\External\Database\MongodbClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'mongodb:database:drop', aliases: ['m:d:d'], hidden: false)]
class DropDatabaseCommand extends Command
{
    public function __construct(private readonly MongodbClient $mongodbClient)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Drop the MongoDB database')
            ->setHelp('This command allows you to drop database collections.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mongodbClient->selectDatabase($_ENV['MONGODB_DB'])->drop();
        $output->writeln('<info>Database dropped successfully.</info>');
        return Command::SUCCESS;
    }
}