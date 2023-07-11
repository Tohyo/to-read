<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    public const TO_READ = 1;
    public const READ = 2;
    public const ARCHIVED = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column]
    public int $status = self::TO_READ;

    public function __construct(
        #[ORM\Column(length: 255)]
        public string $title,

        #[ORM\Column(length: 255)]
        public string $url,

        #[ORM\Column(length: 255, nullable: true)]
        public string $imageUrl,

        #[ORM\Column(type: "text", nullable: true)]
        public string $description
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }
}
