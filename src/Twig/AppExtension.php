<?php

namespace App\Twig;

use App\Entity\Page;
use App\Repository\AdherentRepository;
use Twig\TwigFunction;
use App\Repository\PageRepository;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    public function __construct(private PageRepository $pageRepository, private AdherentRepository $adherentRepository)
    {}

    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pages', [$this, 'getPages']),
            new TwigFunction('totalCurrentAdherent', [$this,'getTotalCurrentAdherent']),
            new TwigFunction('adherentsPerBranch', [$this,'getAdherentsPerBranch']),
            new TwigFunction('adherentsPerAdhesion', [$this,'getAdherentsPerAdhesion']),
            new TwigFunction('sumAdhesions', [$this,'getsumAdhesions']),
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

    public function getTotalCurrentAdherent(): ?int
    {
        return $this->adherentRepository->findCountAdherents();
    }

    public function getAdherentsPerBranch(): ?array
    {
        return $this->adherentRepository->countAdherentsPerBranch();
    }

    public function getAdherentsPerAdhesion(): ?array
    {
        return $this->adherentRepository->countAdherentsPerAdhesion();
    }

    public function getsumAdhesions(): ?int
    {
        return $this->adherentRepository->sumAdhesions();
    }
}
