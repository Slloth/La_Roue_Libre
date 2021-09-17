<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
    * Returns all public Article 
    * 
    * @return Article[]|null Returns an array of Article objects
    */
    public function findAllPublic(): ?array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->andWhere('p.publicatedAt <= :date')
            ->setParameters(['status' => 'Publique','date' => new \DateTime()])
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * Return one public Article
     *
     * @param string $slug
     * @return Article|null
     */
    public function findOnePublic(string $slug): ?Article
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->andWhere('p.status = :status')
            ->andWhere('p.publicatedAt <= :date')
            ->setParameters(['slug' => $slug ,'status' => 'Publique','date' => new \DateTime()])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function searchArticle($search)
    {
        if($search){
            $query = $this->createQueryBuilder("a")
                ->andWhere('a.status = :status')
                ->setParameter("status","Publique")
                ;

            if($search->get("search")->getData() !== null)
            {
                $query = $query
                    ->andWhere("MATCH_AGAINST(a.name,a.content) AGAINST(:search boolean)>0")
                    ->setParameter("search", $search->get("search")->getData())
                    
                ;
            }
            if(!empty($search->get("categories")->getData()[0]))
            {
                $query = $query
                    ->select('a','c')
                    ->join('a.category',"c")
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories', $search->get("categories")->getData())
                ;
            }
            
        return $query->getQuery()->getResult();
        }
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
