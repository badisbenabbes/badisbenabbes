<?php

namespace App\Controller;

use App\Product\Dto\CreateProduct;
use App\Product\Dto\UpdateProduct;
use App\Product\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private ProductService $productService
    ) {
    }

    #[Route('', name: 'create_product', methods: ['POST'])]
    public function create(Request $req): JsonResponse
    {
        $data = json_decode($req->getContent(), true) ?? [];
        $dto = new CreateProduct(
            $data['name'] ?? null,
            $data['price'] ?? null
        );

        $errors = $this->validator->validate($dto);
        if (count($errors)) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $product = $this->productService->create($dto);

        return $this->json($product, 201);
    }

    #[Route('/{id}', name: 'get_product', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $product = $this->productService->get($id);
        if (!$product) {
            return $this->json(['message' => 'Not found'], 404);
        }

        return $this->json($product);
    }

    #[Route('/{id}', name: 'put_product', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new UpdateProduct(
            $data['name'] ?? null,
            $data['price'] ?? null
        );

        $errors = $this->validator->validate($dto);
        if (count($errors)) {
            return $this->json(['errors' => (string)$errors], 400);
        }

        $product = $this->productService->update($id, $dto);
        if (!$product) {
            return $this->json(['message' => 'Not found'], 404);
        }

        return $this->json($product);
    }
}
