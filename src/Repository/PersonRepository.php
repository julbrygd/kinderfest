<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\StartZeit;
use App\Entity\StartPunkt;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @retrun boolval
     */
    public function checkIfPersonRegistered(string $prename, string $name, string $address, int $plz){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT COUNT(p) as anz FROM App\Entity\Person p
             WHERE p.pre_name = :pre_name AND p.name = :s_name AND p.adresse = :s_address AND p.plz = :plz"
        )->setParameter("pre_name", $prename)->setParameter("s_name", $name)
        ->setParameter("s_address", $address)->setParameter("plz", $plz);
        return $query->getSingleResult()["anz"] >= 1;
    }

    /**
     * @retrun int
     */
    public function countPersonForStartZeitAndPunkt(StartZeit $startZeit, StartPunkt $startPunkt){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT COUNT(p) as anz FROM App\Entity\Person p
             WHERE p.start_zeit = :start_zeit AND p.startPunkt = :start_punkt"
        )->setParameter("start_zeit", $startZeit)->setParameter("start_punkt", $startPunkt);
        return $query->getSingleResult()["anz"];
    }

    // /**
    //  * @return Person[] Returns an array of Person objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Person
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
