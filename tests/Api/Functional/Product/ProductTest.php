<?php

namespace CQRS\Tests\Api\Functional\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductTest extends WebTestCase
{
    public function testApiResult()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(202, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('version', $responseData);
        $this->assertNotEmpty($responseData['version']);
        $this->assertNotEmpty($responseData['status']);
    }
}