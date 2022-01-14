<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SplFileObject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class AdherentController extends AbstractController
{
    #[Route('/admin/adherent', name: 'admin_adherent')]
    public function index(): Response
    {
        $path = $this->getParameter('kernel.project_dir').'/var/Détail pièces clients.txt';
        

        return $this->render('admin/adherent/index.html.twig', [
            'test' => $this->readFileAdh($path),
        ]);
    }

    private function readFileAdh($path): Array
    {
        $fields = [];
        if(file_exists($path)){
            // Recupère toutes les lignes ne commencent pas par un #
            $file = new SplFileObject($path);
            while (!$file->eof()){
                // On affiche pas la dernière ligne qui sera toujours une ligne vide 
                $fields[] = explode("\t",$file->fgets());
            }
            $file = null;
        }
        //dd($fields);
        return $fields;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('nom')
            ->add('date')
        ;
    }
}
