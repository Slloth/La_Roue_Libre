<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PageRepository;
use App\Service\CommentService;
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
        private CommentService $commentService,)
    {}
    
    #[Route('/', methods: ['GET','POST'], name: 'home')]
    public function index(Request $request): Response
    {
        $home = $this->pageRepository->findOneBy(['slug'=> 'accueil', 'status' => 'Publique']);

        if($home === null){
            $adminRoute = $request->getSchemeAndHttpHost();
            $adminRoute .= $this->generateUrl('admin');
            throw new NotFoundHttpException("Aucune page Accueil n'as été trouvé, veulliez vous rendre sur " .$adminRoute . " Pour en créer une.");
        }

        $form = $this->createForm(CommentType::class);

        $formRequest = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->commentService->persistComment($formRequest,$home);
            return $this->redirectToRoute('home');
        }
        $currentURL = "accueil";

        return $this->render('page/index.html.twig', [
            'page' => $home,
            'commentsList' => $this->commentRepository->findBy(["page" => $home,"isChecked" => true]),
            'form' => $form->createView(),
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/page/{slug}', methods: ['GET','POST'], name: 'page')]
    public function renderPage(string $slug, Request $request): Response
    {
        $page = $this->pageRepository->findOnePublic($slug);
        
        $form = $this->createForm(CommentType::class);

        $formRequest = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->commentService->persistComment($formRequest,$page);
            return $this->redirectToRoute('page',["slug" => $page->getSlug()]);
        }

        $currentURL = substr($request->getRequestUri(),6);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
            'commentsList' => $this->commentRepository->findBy(["page" => $page,"isChecked" => true]),
            'form' => $form->createView(),
            'currentURL' => $currentURL
        ]);
    }
}
