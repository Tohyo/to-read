<?php

namespace App\MessageHandler;

use App\Entity\Article;
use App\Message\ArchiveStalledArticles;
use App\Repository\ArticleRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ArchiveStalledArticlesHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ArticleRepository $articleRepository
    ) {}

    public function __invoke(ArchiveStalledArticles $message)
    {
        foreach ($message->articles as $article) {
            $response = $this->httpClient->request('GET', $article->url);

            if ($response->getStatusCode() === 200) {
                continue;
            }

            $article->status = Article::ARCHIVED;
            $this->articleRepository->save($article, true);
        }
    }
}
