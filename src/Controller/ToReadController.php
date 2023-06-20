<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tohyo\OpenGraphBundle\OpenGraph;

class ToReadController extends AbstractController
{
    #[Route('/{status}', name: 'app_to_read', methods: ['GET'], defaults: ['status' => Article::TO_READ], requirements: ['status' => '\d+'])]
    public function index(ArticleRepository $articleRepository, int $status): Response
    {
        return $this->render('to_read/index.html.twig', [
            'articles' => $articleRepository->findBy(['status' => $status]),
            'tab' => $status
        ]);
    }

    #[Route('/articles', name: 'app_to_read_articles', methods: ['POST'])]
    public function pastes(Request $request, ArticleRepository $articleRepository, OpenGraph $openGraph): Response
    {
        $data = json_decode($request->getContent(), true);

        $openGraphData = $openGraph->getData($data['url']);

        $article = new Article(
            $openGraphData->title,
            $data['url'],
            $openGraphData->image->url,
            $openGraphData->description ?? ''
        );

        $articleRepository->save($article, true);

        return $this->render('to_read/index.html.twig', [
            'articles' => $articleRepository->findBy(['status' => Article::TO_READ]),
            'tab' => Article::TO_READ
        ]);
    }

    #[Route('/read/{id}', name: 'app_read_article')]
    public function read(Article $article, ArticleRepository $articleRepository): Response
    {
        $article->status = Article::READ;
        $articleRepository->save($article, true);

        return $this->render('to_read/index.html.twig', [
            'articles' => $articleRepository->findBy(['status' => Article::TO_READ]),
            'tab' => Article::TO_READ
        ]);
    }
}
