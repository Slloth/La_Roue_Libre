<?php

namespace App\Repository;

use App\Entity\SouscriptionAdhesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SouscriptionAdhesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SouscriptionAdhesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SouscriptionAdhesion[]    findAll()
 * @method SouscriptionAdhesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SouscriptionAdhesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SouscriptionAdhesion::class);
    }

    ///**
   // * @return SouscriptionAdhesion Returns an array of SouscriptionAdhesion objects
    //*/

    /*
    public function findOneBySomeField($value): ?SouscriptionAdhesion
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
