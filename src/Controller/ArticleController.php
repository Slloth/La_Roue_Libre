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
    /**
     * Constructor
     *
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private PaginatorInterface $paginator,
    )
    {}

    /**
     * Affiche tout les articles
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/articles',methods: ['GET'], name: 'articles')]
    public function index(Request $request): Response
    {

        $articles = $this->articleRepository->findAllPublic();

        $form = $this->createForm(SearchArticleType::class);
        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if($search->get("search")->getData() !== null || !empty($search->get("categories")->getData()[0]) ){
                $articles = $this->articleRepository->searchArticle($search);
            }
        }

        $currentURL = "articles";
        return $this->render('article/index.html.twig', [
            'articles' => $this->paginator->paginate($articles,$request->query->getInt('page',1),66),
            'form' => $form->createView(),
            'currentURL' => $currentURL
        ]);
    }

    /**
     * Affiche un article en fonction du slug passé dans l'URL
     *
     * @param string $slug
     * @param CommentService $commentService
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/article/{slug}',methods: ['GET','POST'], name: 'article')]
    public function show(
        string $slug,
        CommentService $commentService,
        CommentRepository $commentRepository,
        Request $request
        ): Response
    {
        $article = $this->articleRepository->findOnePublic($slug);

        $form = $this->createForm(CommentType::class);

        $formRequest = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentService->persistComment($formRequest,null,$article);
            return $this->redirectToRoute('article',["slug" => $article->getSlug()]);
        }

        $comments = $commentRepository->findBy(["article" => $article,"isChecked" => true]);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentsList' => $this->paginator->paginate($comments,$request->query->getInt('page',1),1),
            'form' => $form->createView(),
        ]);
    }
}
