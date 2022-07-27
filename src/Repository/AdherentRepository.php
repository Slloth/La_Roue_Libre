<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */
    public function findCurrentsAdherentsForEmail()
    {
        return $this->createQueryBuilder('adhr')
            ->select('adhr','adhe')
            ->join('adhr.adhesions',"adhe")
            ->andWhere('adhr.email IS NOT NULL')
            ->andWhere("DATE_ADD(adhe.subscribedAt, 1, 'YEAR') > :currentDate")
            ->setParameter('currentDate', new \DateTime())
            ->orderBy('adhe.subscribedAt', 'DESC')
            ->getQuery()
            //->setMaxResults(1)
            ->getResult()
        ;
    }

    public function findCurrentsAdherentsForExport(): ORMQueryBuilder
    {
        return $this->createQueryBuilder('adhr')
            ->select('adhr','adhe')
            ->join('adhr.adhesions',"adhe")
            ->andWhere("DATE_ADD(adhe.subscribedAt, 1, 'YEAR') > :currentDate")
            ->setParameter('currentDate', new \DateTime())
            ->orderBy('adhe.subscribedAt', 'DESC')
        ;
    }



    /* STATS REPOSITORY */


    /**
     * @return int
     */
    public function findCountAdherents()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(*) FROM `adherentsWithLastSub`";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }

    /**
     * @return Adherent[]
     */
    public function countAdherentsPerBranch()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(branch) AS value, branch AS label FROM `adherentsWithLastSub` GROUP BY branch";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Adherent[]
     */
    public function countAdherentsPerAdhesion()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(prix) AS value, prix AS label FROM `adherentsWithLastSub` GROUP BY prix";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return int
     */
    public function sumAdhesions()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT SUM(prix) AS value, lastSub AS label FROM `adherentsWithLastSub`";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }
}
