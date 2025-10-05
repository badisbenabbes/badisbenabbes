<?php

namespace App\Product\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProduct
{
    public function __construct(
        public ?string $name = null,

        #[Assert\Type(type: 'numeric', message: 'Le prix doit être un nombre.')]
        #[Assert\Positive(message: 'Le prix doit être supérieur à 0.')]
        public ?float $price = null,
    ) {
    }
}
