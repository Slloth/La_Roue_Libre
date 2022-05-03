<?php
namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    public function createResponseFromQueryBuilder(QueryBuilder $queryBuilder, FieldCollection $fields, string $filename): Response
    {
        $result = $queryBuilder->getQuery()->getResult();
        $data = [];
        foreach($result as $index => $row) {
            foreach ((array) $row as $columnKey => $columnValue) {
                
                $columnKey = substr($columnKey,21);
                
                if($columnValue instanceof \DateTimeInterface){
                    // Convert DateTime objects into strings
                    $data[$index][$columnKey] = $columnValue->format('Y-m-d H:i:s');
                }
                else if($columnValue instanceof Collection){
                    $i=1;
                    foreach($columnValue as $adhesion){
                        $data[$index]["Adhesion N°". $i] = $adhesion->getPrix();
                        // Convert DateTime objects into strings
                        $data[$index]["Souscription N°". $i] = $adhesion->getSubscribedAt()->format('Y-m-d H:i:s');
                        $data[$index]["Emplacement N°". $i] = $adhesion->getBranch();
                    
                        $i++;
                    }
                }
                else{
                    $data[$index][$columnKey] = $columnValue;
                }
            }
        }

        // Reorganize the data's array per descending order and reset index of this array 
        arsort($data);
        $data = array_values($data);

        // Humanize headers based on column labels in EA
        $headers = [];
        if (isset($data[0])) {
            $properties = array_keys($data[0]);
            foreach ($properties as $property) {
                $headers[$property] = ucfirst($property);
                foreach ($fields as $field) {
                    // Override property name if a custom label was set
                    if ($property === $field->getProperty() && $field->getLabel()) {
                        $headers[$property] = $field->getLabel();
                        // And stop iterating
                        break;
                    }
                }
            }
            // Add headers to the final data array
            array_unshift($data, $headers);
        }

        // Fill data's array with null value for each header
        for($i=0;$i<count($data);$i++){
            foreach($headers as $headerkey => $headerValue){
                if(!isset($data[$i][$headerkey])){
                    $data[$i][$headerkey] = null;
                }
            }
        }

        $response = new StreamedResponse(function () use ($data) {
            $config = new ExporterConfig();
            $exporter = new Exporter($config);
            $exporter->export('php://output', $data);
        });
        $dispositionHeader = $response->headers->makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Disposition', $dispositionHeader);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        return $response;
    }
}