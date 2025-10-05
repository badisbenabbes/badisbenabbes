<?php

namespace App\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    #[DataProvider('provideValidProducts')]
    public function testCreateProductSuccess(array $payload): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame($payload['name'], $data['name']);
        $this->assertEquals($payload['price'], $data['price']);
    }

    #[DataProvider('provideInvalidProducts')]
    public function testCreateProductFail(array $payload): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
    }

    public function testGetProductSuccess(): void
    {
        $this->client->request(
            method: 'POST',
            uri:'/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'name' => 'Get Test',
                'price' => 42,
                ]
            )
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $id = $data['id'];

        $this->client->request('GET', '/api/products/' . $id);
        $this->assertResponseStatusCodeSame(200);

        $retrieved = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertSame('Get Test', $retrieved['name']);
        $this->assertSame(42, $retrieved['price']);
    }

    public function testGetProductNotFound(): void
    {
        $this->client->request('GET', '/api/products/0');

        $this->assertResponseStatusCodeSame(404);
    }

     #[DataProvider('provideUpdatePayloads')]
    public function testUpdateProductSuccess(array $updatePayload, callable $assertion): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'name' => 'Initial Product',
                'price' => 10.0,
                ]
            )
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $id = $data['id'];

        $this->client->request(
            method: 'PUT',
            uri: '/api/products/' . $id,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($updatePayload)
        );

        $this->assertResponseStatusCodeSame(200);
        $updated = json_decode($this->client->getResponse()->getContent(), true);
        $assertion($updated);
    }

    public function testUpdateProductFail(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/products',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'name' => 'Invalid Update',
                'price' => 10.0,
                ]
            )
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $id = $data['id'];

        $this->client->request(
            method: 'PUT',
            uri: '/api/products/' . $id,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['price' => -1])
        );

        $this->assertResponseStatusCodeSame(400);
        $body = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $body);
    }

    public static function provideUpdatePayloads(): iterable
    {
        yield 'update only name' => [
            ['name' => 'New Name'],
            function (array $data) {
                Assert::assertSame('New Name', $data['name']);
                Assert::assertEquals(10.0, $data['price']);
            }
        ];

        yield 'update only price' => [
            ['price' => 25.50],
            function (array $data) {
                Assert::assertSame('Initial Product', $data['name']);
                Assert::assertEquals(25.50, $data['price']);
            }
        ];

        yield 'update name and price' => [
            ['name' => 'Updated Product', 'price' => 35.0],
            function (array $data) {
                Assert::assertSame('Updated Product', $data['name']);
                Assert::assertEquals(35.0, $data['price']);
            }
        ];
    }

    public static function provideValidProducts(): iterable
    {
        yield 'standard product' => [['name' => 'Product price 99.99', 'price' => 99.99]];
        yield 'cheap product' => [['name' => '1.00', 'price' => 1.00]];
        yield 'expensive product' => [['name' => 'Product price 10000.00', 'price' => 10000.00]];
    }

    public static function provideInvalidProducts(): iterable
    {
        yield 'without name' => [['price' => 50.0]];
        yield 'without price' => [['name' => 'name']];
        yield 'negative price' => [['name' => 'bad Price', 'price' => -5]];
        yield 'empty name' => [['name' => '', 'price' => 20]];
    }
}
