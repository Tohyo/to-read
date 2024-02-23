<?php

namespace App\Dto;

use App\Entity\Category;

class CreateArticleDto
{
    public function __construct(
        public ?string $url = null,
        public ?Category $category = null
    ) {
    }
}
