<?php

namespace App\Controller\Admin;

use App\Form\EnvType;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnvController extends AbstractController
{
    #[Route('/admin/env', name: 'admin_env')]
    public function index(Request $request): Response
    {
        $path = $this->getParameter('kernel.project_dir').'/.env.local';

        $form = $this->createForm(EnvType::class,$this->readEnv($path));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $handle = fopen($path,'w');
            $childs = $form->all();
            foreach($childs as $child)
            {
                if($child->getData() !== null){
                    fwrite($handle, strtoupper($child->getName())."=".$child->getData()."\n");
                }
            }

            fclose($handle);
            $this->addFlash("success","Les variables d'environnements ont bien été modifié!");
            return $this->redirectToRoute("admin");
        }

        return $this->render('admin/env/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Le chemin du fichier .env de préférence utiliser le fichier .env.local
     *
     * @param string $path
     * @return Array
     */
    private function readEnv($path): Array
    {
        $fields = [];
        if(file_exists($path)){
            // Recupère toutes les lignes ne commencent pas par un #
            $file = new SplFileObject($path);
            while ($line = $file->fgets()){
                if(!empty($line) || $line !== "#")
                    // Ont affiche pas la dernière ligne qui sera toujours une ligne vide 
                    $fields[] = $line;
            }
            $file = null;
        }
        return $fields;
    }
}
