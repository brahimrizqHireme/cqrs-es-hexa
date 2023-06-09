<?php

namespace CQRS\Common\Infrastructure\Cli;

use CQRS\DataFixture\DemoFixtures;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'mongodb:fixtures:load', aliases: ['m:f:l'], hidden: false)]
class FixtureLoadCommand extends Command
{
    public function __construct(
        private readonly DemoFixtures $demoFixtures
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Load data fixtures into database')
            ->addArgument('skipDropDb', InputArgument::OPTIONAL, 'Skip dropping the database (default: false)');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $skipDropDb = (bool) $input->getArgument('skipDropDb');

        if (!$skipDropDb) {
            $this->dropDatabase($output);
        }

        $this->demoFixtures->loadFixtures();
        $output->writeln('<info>Fixtures Finished successfully.</info>');
        return Command::SUCCESS;
    }

    /**
     * @throws ExceptionInterface
     */
    private function dropDatabase(OutputInterface $output): void
    {
        $command = $this->getApplication()->find('mongodb:database:drop');
        $command->run(new ArrayInput([]), $output);
    }
}