<?php

namespace CQRS\Tests\Api\Functional\Product;

use CQRS\Common\Domain\Helper\CommonService;
use CQRS\Common\Infrastructure\Cli\BackupMongoDbCommand;
use CQRS\Common\Infrastructure\Cli\DropDatabaseCommand;
use CQRS\Common\Infrastructure\Cli\RestoreDatabaseCommand;
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
    private string $projectDir;
//    private ResetInterface $application;


    protected static function single_exec($c): string
    {
        if (is_null($output = shell_exec($c))) {
            throw new \Exception("Error while executing $c.");
        }

        return $output;
    }

    public static function showMessage(string $message): void
    {
        if('quite' !== getenv('EXEC_MODE')) {
            echo($message . PHP_EOL);
        }
    }

    private static function restoreDb(): void
    {
        self::showMessage(' ----> Prepare Demo Data <---- ');
        try {
            self::single_exec('make restore-db');
        } catch (\Exception $exception) {
            echo sprintf('%s', $exception->getMessage());
        }
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->container = $kernel->getContainer();
        $this->projectDir = $kernel->getProjectDir();
        $this->client = $this->container->get('test.client');
        self::restoreDb();
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