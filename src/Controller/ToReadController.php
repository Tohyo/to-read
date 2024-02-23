<?php

namespace App\Controller;

use App\Dto\CreateArticleDto;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\CreateArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tohyo\OpenGraphBundle\OpenGraph;

class ToReadController extends AbstractController
{
    #[Route('/', name: 'app_to_read')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        OpenGraph $openGraph
    ): Response {
        $response = null;
        $articleDto = new CreateArticleDto();
        $form = $this->createForm(CreateArticleType::class, $articleDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $openGraphData = $openGraph->getData($articleDto->url);

            $article = new Article(
                $openGraphData->title ?? $articleDto->url,
                $articleDto->url,
                $openGraphData->image->url ?? '',
                $openGraphData->description ?? '',
                $articleDto->category
            );

            $articleRepository->save($article, true);

            $response = new Response(null, Response::HTTP_CREATED);
        }

        return $this->render('to_read/index.html.twig', [
            'form' => $form,
            'articlesToSort' => $em->getRepository(Article::class)->findBy([
                'category' => null
            ]),
            'categories' => $categoryRepository->findAll(),
            'currentCategory' => $categoryRepository->find(17)
        ], $response);
    }

    #[Route('/articles/{category}', name: 'articles_for_category')]
    public function articlesByCategories(
        Category $category,
        CategoryRepository $categoryRepository
    ): Response {
        return $this->render('to_read/_tabs.html.twig', [
            'currentCategory' => $category,
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
