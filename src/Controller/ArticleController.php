<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{

    #[Route('/articles',methods: ['GET'], name: 'articles')]
    public function index(
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
        $articles = $articleRepository->findAllPublic();
        $articles = $paginator->paginate($articles,$request->query->getInt('page',1),12);
        
        $currentURL = "articles";
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/article/{slug}',methods: ['GET'], name: 'article')]
    public function show(string $slug, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOnePublic($slug);
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}
