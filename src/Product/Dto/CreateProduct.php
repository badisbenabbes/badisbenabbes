<?php

namespace App\Product\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProduct
{
    public function __construct(
        #[Assert\NotBlank(message: 'Le nom du produit est obligatoire.')]
        public ?string $name,

        #[Assert\NotNull(message: 'Le prix est obligatoire.')]
        #[Assert\Type(type: 'numeric', message: 'Le prix doit être un nombre.')]
        #[Assert\Positive(message: 'Le prix doit être supérieur à 0.')]
        public ?float $price,
    ) {
    }
}
