<?php

namespace App\Twig;

use App\Repository\PageRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $pageRepository;

    public function __construct(PageRepository $pageRepository){
        $this->pageRepository = $pageRepository;
    }

    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pages', [$this, 'getPages']),
        ];
    }

    public function getPages()
    {
        return $this->pageRepository->findAllPublic();
    }
}
