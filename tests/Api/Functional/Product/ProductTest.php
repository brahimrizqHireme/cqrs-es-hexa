<?php

namespace CQRS\Tests\Api\Functional\Product;

use CQRS\Kernel;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductTest extends KernelTestCase
{
    private ContainerInterface $container;
    private HttpClientInterface $client;

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {

        parent::setUp();
        self::bootKernel();
        $this->container = self::getContainer();
        $currentHost = $_SERVER['HTTP_HOST'];
        dd($currentHost);
        $this->client = HttpClient::create();
    }

    private function sendARequest(
        string $url,
        string $method,
        array $payload,
        array $headers = [],
        int $expectedStatusCode = Response::HTTP_OK,
        bool $showResponse = false
    ): array {

        $headers['Content-Type'] = 'application/json';

        $response = $this->client->request($method, $url, [
            'json' => $payload,
            'headers' => $headers
        ]);

        // Assert the expected status code
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        // Optionally display the response content for debugging
        if ($showResponse) {
            echo sprintf(
                "[DEBUG] Request:\n  Method: %s\n  URL: %s\n  Status Code: %d\n\nResponse Body:\n%s\n",
                $method,
                $url,
                $response->getStatusCode(),
                $response->getContent()
            );
        }

        return $response->toArray();
    }

    public function testApiResult(): void
    {
        $responseData = $this->sendARequest(
            '/api/v1',
            'GET',
            [],
            [],
            Response::HTTP_OK,
            true
        );

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('version', $responseData);
        $this->assertNotEmpty($responseData['version']);
        $this->assertNotEmpty($responseData['status']);
    }
}