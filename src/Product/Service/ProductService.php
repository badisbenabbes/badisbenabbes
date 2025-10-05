<?php

namespace App\Product\Service;

use App\Product\Dto\CreateProduct;
use App\Product\Dto\UpdateProduct;
use App\Entity\Product;
use App\Product\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SkuGenerator $skuGenerator,
        private readonly ProductMapper $productMapper,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function create(CreateProduct $dto): array
    {
        $product = (new Product())
            ->setName($dto->name)
            ->setSku($this->skuGenerator->generate($dto->name))
            ->setPrice($dto->price)
        ;

        $this->em->persist($product);
        $this->em->flush();

        return $this->productMapper->toArray($product);
    }

    public function get(string $id): ?array
    {
        if (!Uuid::isValid($id)) {
            return null;
        }

        $product = $this->productRepository->find($id);

        return $product ? $this->productMapper->toArray($product) : null;
    }

    public function update(string $id, UpdateProduct $dto): ?array
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            return null;
        }

        if ($dto->name) {
            $product->setName($dto->name);
        }
        if ($dto->price) {
            $product->setPrice($dto->price);
        }

        $this->em->flush();

        return $this->productMapper->toArray($product);
    }
}
