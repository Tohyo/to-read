<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    public function __construct(
        #[ORM\Column(length: 255)]
        public string $title,

        #[ORM\Column(length: 255)]
        public string $url,

        #[ORM\Column(length: 255, nullable: true)]
        public string $imageUrl,

        #[ORM\Column(type: "text", nullable: true)]
        public string $description,

        #[ORM\ManyToOne(inversedBy: 'articles')]
        public ?Category $category = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
