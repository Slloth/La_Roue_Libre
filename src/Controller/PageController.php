<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', methods: ['GET'], name: 'home')]
    public function index(PageRepository $pageRepository): Response
    {
        $home = $pageRepository->findOneBy(['slug'=> 'accueil', 'status' => 'Publique']);

        $currentURL = "accueil";

        return $this->render('page/index.html.twig', [
            'page' => $home,
            'currentURL' => $currentURL
        ]);
    }

    #[Route('/page/{slug}', methods: ['GET'], name: 'page')]
    public function renderPage(string $slug, PageRepository $pageRepository, Request $request): Response
    {
        $page = $pageRepository->findOneBy(['slug'=> $slug, 'status' => 'Publique']);
        
        $currentURL = substr($request->getRequestUri(),6);
        
        return $this->render('page/index.html.twig', [
            'page' => $page,
            'currentURL' => $currentURL
        ]);
    }
}
