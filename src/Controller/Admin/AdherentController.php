<?php

namespace App\Controller\Admin;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdherentController extends AbstractController
{
    #[Route('/admin/adherent', name: 'admin_adherent')]
    public function index(Request $request): Response
    {
        $path = $this->getParameter('kernel.project_dir').'/var/Détail pièces clients.txt';

        return $this->render('admin/adherent/index.html.twig',[
            "records" => $this->recordsAction($path, $request)
        ]);
    }

    
    public function recordsAction($path, Request $request)
    {
        $search = $request->get('search',null);
        $order = $request->get('order',null);
        $limit = $request->get('limit',null);
        
        $records = $this->readFileAdh($path);
        
        $searchableFields = $records[0];
        
        $words = explode(" ",$search);
        
        $result = [];
        
        if($search != null)
        {
            foreach($records[1] as $line)
            {
                foreach($searchableFields as $searchableField)
                {
                    foreach($words as $word)
                    {
                        if(str_contains(
                            strtolower($line[$searchableField]),
                            strtolower($word))
                            )
                            {
                                $result[] = $line;
                            } 
                        }
                    }
                }
                $records[1] = $result;
            }
            //dd($records);
            return $records;
        }
        
        private function readFileAdh($path): Array
        {
            $fields = [];
            $drapeau = false;
            $headers = [];
            $i = 0;
            if(file_exists($path)){
    
                // On ouvre le fichier
                $file = new SplFileObject($path);
    
                // On affiche pas la dernière ligne qui sera toujours une ligne vide
                while (!$file->eof()){
                    if(!$drapeau)
                    {
                        // On Récupère les en-têtes
                        $headers = explode("\t",$file->fgets());
                        $drapeau = true;
                    }
                    // On récupère les valeurs
                    $tmp = explode("\t",$file->fgets());
    
                    for($j=0; $j< count($headers);$j++)
                    {
                        // On créer un nouveau tableau qui lie chaque valeur à son en-tête
                        $fields[$i][$headers[$j]] = $tmp[$j];
                    }
                    $i++;
                }
                // On ferme la connexion au fichier
                $file = null;
            }
            return [$headers,$fields];
        }

        private function unique_multidim_array($array, $key) {
            $temp_array = array();
            $i = 0;
            $key_array = array();
        
            foreach($array as $val) {
                if (!in_array($val[$key], $key_array)) {
                    $key_array[$i] = $val[$key];
                    $temp_array[$i] = $val;
                }
                $i++;
            }
            return $temp_array;
        }
}
