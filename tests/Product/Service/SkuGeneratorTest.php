<?php

namespace App\Tests\Product\Service;

use App\Product\Service\SkuGenerator;
use PHPUnit\Framework\TestCase;

class SkuGeneratorTest extends TestCase
{
    private SkuGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = new SkuGenerator();
    }

    public function testGenerateSuccess(): void
    {
        $sku = $this->generator->generate('Macbook Pro');

        $this->assertNotEmpty($sku, 'SKU should not be empty');
        $this->assertStringStartsWith('PROD-MACB-', $sku, 'SKU should start with PROD-MACB-');
        $this->assertGreaterThanOrEqual(15, strlen($sku), 'SKU length should be at least 15 characters');
        $this->assertIsString($sku, 'SKU should be a string');
    }

    public function testGenerateFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product name cannot be empty.');

        $this->generator->generate('');
    }
}
