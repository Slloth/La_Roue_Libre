<?php

namespace App\Repository;

use App\Entity\TypeAdhesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeAdhesion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAdhesion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAdhesion[]    findAll()
 * @method TypeAdhesion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAdhesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAdhesion::class);
    }
}
