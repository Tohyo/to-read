<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\OpenGraph;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToReadController extends AbstractController
{
    #[Route('/', name: 'app_to_read')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('to_read/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/articles', name: 'app_to_read_articles', methods: ['POST'])]
    public function pastes(Request $request, ArticleRepository $articleRepository, OpenGraph $openGraph): Response
    {
        $data = json_decode($request->getContent(), true);

        $article = new Article(
            $openGraph($data['url'])['title'],
            $data['url']
        );

        $articleRepository->save($article, true);

        return $this->render('to_read/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
}
