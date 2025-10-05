<?php

namespace App\Product\Service;

final class SkuGenerator
{
    /**
     * Generate SKU like: PROD-MACB-a8d3f1c from a product name Macbook Pro.
     */
    public function generate(string $productName): string
    {
        $productName = trim($productName);
        if ($productName === '') {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }

        $prefix = strtoupper(substr($productName, 0, 4));
        // @link https://www.php.net/manual/fr/function.random-bytes.php
        $rand = substr(bin2hex(random_bytes(4)), 0, 7);

        return sprintf('PROD-%s-%s', $prefix, $rand);
    }
}
