<?php

namespace App\Repository;

use App\Entity\StartZeit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StartZeit|null find($id, $lockMode = null, $lockVersion = null)
 * @method StartZeit|null findOneBy(array $criteria, array $orderBy = null)
 * @method StartZeit[]    findAll()
 * @method StartZeit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartZeitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StartZeit::class);
    }

    // /**
    //  * @return StartZeit[] Returns an array of StartZeit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StartZeit
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
