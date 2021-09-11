<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Flex\Path;

class PageController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'home')]
    public function index(PageRepository $pageRepository, Request $request): Response
    {
        $home = $pageRepository->findOneBy(['slug'=> 'accueil', 'status' => 'Publique']);

        if($home === null){
            $adminRoute = $request->getSchemeAndHttpHost();
            $adminRoute .= $this->generateUrl('admin');
            throw new NotFoundHttpException("Aucune page Accueil n'as été trouvé, veulliez vous rendre sur " .$adminRoute . " Pour en créer une.");
        }

        $currentURL = "accueil";

        return $this->render('page/index.html.twig', [
            'page' => $home,
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/page/{slug}', methods: ['GET'], name: 'page')]
    public function renderPage(string $slug, PageRepository $pageRepository, Request $request): Response
    {
        $page = $pageRepository->findOnePublic($slug);
        
        $currentURL = substr($request->getRequestUri(),6);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
            'currentURL' => $currentURL
        ]);
    }
}
