<?php

namespace App\Product\Mapper;

use App\Entity\Product;

class ProductMapper
{
    public function toArray(Product $product): array
    {
        return [
            'id' => (string) $product->getId(),
            'name' => $product->getName(),
            'sku' => $product->getSku(),
            'price' => $product->getPrice(),
            'createdAt' => $product->getCreatedAt()->format('c'),
            'updatedAt' => $product->getUpdatedAt()?->format('c'),
        ];
    }
}
