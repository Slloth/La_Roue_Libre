<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
    * Returns all public Page 
    * 
    * @return Page[]|null Returns an array of Page objects
    */
    public function findAllPublic(): ?array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug != :accueil')
            ->andWhere('p.status = :status')
            ->andWhere('p.publicatedAt >= :date')
            ->addOrderBy('p.name','ASC')
            ->setParameters(['status' => 'Publique','date' => new \DateTime(),'accueil' => 'accueil'])
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * Return one public Page
     *
     * @param string $slug
     * @return Page|null
     */
    public function findOnePublic(string $slug): ?Page
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->andWhere('p.status = :status')
            ->andWhere('p.publicatedAt >= :date')
            ->setParameters(['slug' => $slug ,'status' => 'Publique','date' => new \DateTime()])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Page
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
