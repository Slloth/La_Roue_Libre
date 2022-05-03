<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\PageRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isNull;

class SitemapController extends AbstractController
{
    /**
     * Génère le sitemap du site web
     *
     * @param Request $request
     * @param PageRepository $pageRepository
     * @param ArticleRepository $articleRepository
     * 
     * @return Response
     */
    #[Route('/sitemap.xml', name: 'sitemap', format: 'xml')]
    public function index(
        Request $request,
        PageRepository $pageRepository,
        ArticleRepository $articleRepository
        ): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [];
        $urls[] = ["loc" => $this->generateUrl('home')];
        $urls[] = ["loc" => $this->generateUrl('app_login')];
        $urls[] = ["loc" => $this->generateUrl('app_logout')];
        $urls[] = ["loc" => $this->generateUrl('app_register')];
        $urls[] = ["loc" => $this->generateUrl('app_verify_email')];
        $urls[] = ["loc" => $this->generateUrl('app_forgot_password_request')];
        $urls[] = ["loc" => $this->generateUrl('app_check_email')];
        $urls[] = ["loc" => $this->generateUrl('app_reset_password')];
        $urls[] = ["loc" => $this->generateUrl('newsletter_register')];
        $urls[] = ["loc" => $this->generateUrl('newsletter_verify_email')];
        $urls[] = ["loc" => $this->generateUrl('contact')];
        $urls[] = ["loc" => $this->generateUrl('articles')];

        foreach($pageRepository->findAllPublic() as $page){
            $urls[] = [
                "loc" => $this->generateUrl('page',["slug" => $page->getSlug()]),
                "lastmod" => $page->getCreatedAt()->format("Y-m-d")
            ];
        }
        
        foreach($articleRepository->findAllPublic() as $article){
            $urls[] = [
                "loc" => $this->generateUrl('article',["slug" => $article->getSlug()]),
                "lastmod" => $article->getCreatedAt()->format("Y-m-d")
            ];
        }

        $response = new Response($this->renderView('sitemap/index.html.twig',[
            "urls" => $urls,
            "hostname" => $hostname
        ]));

        $response->headers->set("Content-type","text/xml");

        return $response;
    }
}
