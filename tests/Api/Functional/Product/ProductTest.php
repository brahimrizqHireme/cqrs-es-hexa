<?php

namespace CQRS\Tests\Api\Functional\Product;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Contracts\Service\ResetInterface;

class ProductTest extends WebTestCase
{
    private ContainerInterface $container;
    private KernelBrowser $client;
    private ResetInterface $application;

    public function runCommand(array $commands): void
    {
        $output = new BufferedOutput();
        foreach ($commands as $command) {
            $input = new ArrayInput([
                'command' => 'app:my-command',
                '--option' => 'value',
                'argument' => 'argument-value',
            ]);

            $this->application->run($input, $output);
        }
        $output->writeln($output->fetch());
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->application = new Application('Console test', '1.0');
        $this->container = static::getContainer();
        $this->client = $this->container->get('test.client');
        parent::setUp();
    }

    public function testApiResult()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('version', $responseData);
        $this->assertNotEmpty($responseData['version']);
        $this->assertNotEmpty($responseData['status']);
    }
}