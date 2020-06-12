<?php

namespace App\Repository;

use App\Entity\Souvenir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Souvenir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Souvenir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Souvenir[]    findAll()
 * @method Souvenir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SouvenirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Souvenir::class);
    }

    // /**
    //  * @return Souvenir[] Returns an array of Souvenir objects
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
    public function findOneBySomeField($value): ?Souvenir
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
