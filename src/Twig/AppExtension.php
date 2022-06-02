<?php

namespace App\Twig;

use App\Entity\Page;
use Twig\TwigFunction;
use App\Repository\PageRepository;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository){
        $this->pageRepository = $pageRepository;
    }

    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pages', [$this, 'getPages']),
        ];
    }

    /**
     * Retourne toutes les pages publiques en une fonction twig
     *
     * @return array<Page>|null
     */
    public function getPages(): ?array
    {
        return $this->pageRepository->findAllPublic();
    }
}
