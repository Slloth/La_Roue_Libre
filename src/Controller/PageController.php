<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Page;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends AbstractController
{
    public function __construct(private CommentRepository $commentRepository, private PageRepository $pageRepository)
    {
        $commentRepository;
        $pageRepository;
    }
    
    #[Route('/', methods: ['GET','POST'], name: 'home')]
    public function index(Request $request): Response
    {
        $home = $this->pageRepository->findOneBy(['slug'=> 'accueil', 'status' => 'Publique']);

        if($home === null){
            $adminRoute = $request->getSchemeAndHttpHost();
            $adminRoute .= $this->generateUrl('admin');
            throw new NotFoundHttpException("Aucune page Accueil n'as été trouvé, veulliez vous rendre sur " .$adminRoute . " Pour en créer une.");
        }

        $currentURL = "accueil";

        return $this->render('page/index.html.twig', [
            'page' => $home,
            'commentsList' => $this->commentRepository->findBy(["page" => $home,"isChecked" => true]),
            'form' => $this->comment($home,$request),
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/page/{slug}', methods: ['GET','POST'], name: 'page')]
    public function renderPage(string $slug, Request $request): Response
    {
        $page = $this->pageRepository->findOnePublic($slug);
        
        $currentURL = substr($request->getRequestUri(),6);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
            'commentsList' => $this->commentRepository->findBy(["page" => $page,"isChecked" => true]),
            'form' =>$this->comment($page,$request),
            'currentURL' => $currentURL
        ]);
    }

    private function comment(Page $page, Request $request): FormView
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommentType::class);

        $formView = $form->createView();

        $commentForm = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment = new Comment();
            
            $comment->setContent($commentForm->get("content")->getData())
                    ->setPage($page)
                    //->setParent()
                    ->setIsChecked(false)
                    ;
            
            $em->persist($comment);
            $em->flush();

            unset($form);
        }
        return $formView;
    }
}
