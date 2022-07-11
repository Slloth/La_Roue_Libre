<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PageRepository;
use App\Service\CommentService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends AbstractController
{
    public function __construct(
        private CommentRepository $commentRepository, 
        private PageRepository $pageRepository, 
        private CommentService $commentService,
        private PaginatorInterface $paginator)
    {}
    
    /**
     * Affiche La page par defaut du site Web
     *
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/', methods: ['GET','POST'], name: 'home')]
    public function index(Request $request): Response
    {
        $home = $this->pageRepository->findOneBy(['slug'=> 'accueil', 'status' => 'Publique']);

        if($home === null){
            $adminRoute = $request->getSchemeAndHttpHost();
            $adminRoute .= $this->generateUrl('admin');
            throw new NotFoundHttpException("Aucune page Accueil n'as été trouvé, veulliez vous rendre sur " .$adminRoute . " Pour en créer une.");
        }

        $commentForm = $this->createForm(CommentType::class);

        $commentFormRequest = $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $this->commentService->persistComment($commentFormRequest,$home);
            return $this->redirectToRoute('home');
        }

        $comments = $this->commentRepository->findBy(["page" => $home,"isChecked" => true]);

        $currentURL = "accueil";

        return $this->render('page/index.html.twig', [
            'page' => $home,
            'commentsList' => $this->paginator->paginate($comments,$request->query->getInt('page',1),6),
            'form' => $commentForm->createView(),
            'currentURL' => $currentURL
        ]);
    }

    /**
     * Affiche La page correspondant à un slug en base de données
     * 
     * @param String $slug
     * @param Request $request
     * 
     * @return Response
     */
    #[Route('/page/{slug}', methods: ['GET','POST'], name: 'page')]
    public function renderPage(string $slug, Request $request): Response
    {
        $page = $this->pageRepository->findOnePublic($slug);
        $commentForm = $this->createForm(CommentType::class);

        $commentFormRequest = $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $this->commentService->persistComment($commentFormRequest,$page);
            return $this->redirectToRoute('page',["slug" => $page->getSlug()]);
        }

        $comments = $this->commentRepository->findBy(["page" => $page,"isChecked" => true]);

        $currentURL = substr($request->getRequestUri(),6);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
            'commentsList' => $this->paginator->paginate($comments,$request->query->getInt('page',1),6),
            'form' => $commentForm->createView(),
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/page/mentions-legales', methods: ['GET'], name: 'mentions-legales')]
    public function privacyPolicy(): Response
    {
        $page = $this->pageRepository->findOneBy(['slug'=> 'mentions-legales', 'status' => 'Publique']);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
        ]);
    }
}
