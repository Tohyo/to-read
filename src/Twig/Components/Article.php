<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Entity\Article as ArticleEntity;

#[AsLiveComponent]
final class Article
{
    use DefaultActionTrait;

    #[LiveProp()]
    public ArticleEntity $article;
}
