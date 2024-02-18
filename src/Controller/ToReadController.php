<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
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
        CategoryRepository $categoryRepository
    ): Response {
        $response = null;
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();

            $response = new Response(null, Response::HTTP_CREATED);
        }

        return $this->render('to_read/index.html.twig', [
            'form' => $categoryForm,
            'articlesToSort' => $em->getRepository(Article::class)->findBy([
                'category' => null
            ]),
            'categories' => $categoryRepository->findAll(),
            'currentCategory' => $categoryRepository->find(1)
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

    #[Route('/articles', name: 'app_to_read_articles', methods: ['POST'])]
    public function pastes(Request $request, ArticleRepository $articleRepository, OpenGraph $openGraph): Response
    {
        $data = json_decode($request->getContent(), true);

        $openGraphData = $openGraph->getData($data['url']);

        $article = new Article(
            $openGraphData->title ?? $data['url'],
            $data['url'],
            $openGraphData->image->url ?? '',
            $openGraphData->description ?? ''
        );

        $articleRepository->save($article, true);

        return $this->render('to_read/_articles_to_sort.html.twig', [
            'articlesToSort' => $articleRepository->findBy([
                'category' => null
            ]),
        ]);
    }
}
