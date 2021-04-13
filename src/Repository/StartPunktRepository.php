<?php

namespace App\Repository;

use App\Entity\StartPunkt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StartPunkt|null find($id, $lockMode = null, $lockVersion = null)
 * @method StartPunkt|null findOneBy(array $criteria, array $orderBy = null)
 * @method StartPunkt[]    findAll()
 * @method StartPunkt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartPunktRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StartPunkt::class);
    }

    // /**
    //  * @return StartPunkt[] Returns an array of StartPunkt objects
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
    public function findOneBySomeField($value): ?StartPunkt
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
