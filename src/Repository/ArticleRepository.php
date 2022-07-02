<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;

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
            ->addOrderBy('p.publicatedAt','DESC')
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

    /**
     * Recherche un article avec un match against en base de données, mais aussi par rapport aux categories selectionnées
     *
     * @param Form $search
     * 
     * @return Article[]|null
     */
    public function searchArticle(Form $search): ?Array
    {
        if($search){
            $query = $this->createQueryBuilder("a")
                ->select('a','c')
                ->leftjoin('a.category',"c")
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
                    ->andWhere('c.id IN (:categories)')
                    ->setParameter('categories', $search->get("categories")->getData())
                ;
            }
            
        return $query->getQuery()->getResult();
        }
    }
}
