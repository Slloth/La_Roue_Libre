<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\CommentService;
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

        $form = $this->createForm(SearchArticleType::class);
        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if($search->get("search")->getData() !== null || !empty($search->get("categories")->getData()[0]) ){
                $articles = $articleRepository->searchArticle($search);
            }
        }

        $currentURL = "articles";
        return $this->render('article/index.html.twig', [
            'articles' => $paginator->paginate($articles,$request->query->getInt('page',1),12),
            'form' => $form->createView(),
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/article/{slug}',methods: ['GET','POST'], name: 'article')]
    public function show(
        string $slug, 
        ArticleRepository $articleRepository, 
        CommentRepository $commentRepository, 
        CommentService $commentService, 
        Request $request
        ): Response
    {
        $article = $articleRepository->findOnePublic($slug);

        $form = $this->createForm(CommentType::class);

        $formRequest = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentService->comment($formRequest,null,$article);
            return $this->redirectToRoute('article',["slug" => $article->getSlug()]);
        }


        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentsList' => $commentRepository->findBy(["article" => $article,"isChecked" => true]),
            'form' => $form->createView(),
        ]);
    }
}
