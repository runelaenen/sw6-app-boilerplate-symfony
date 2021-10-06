<?php

namespace App\Repository;

use App\Entity\SwShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SwShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwShop[]    findAll()
 * @method SwShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwShop::class);
    }

    // /**
    //  * @return SwShop[] Returns an array of SwShop objects
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
    public function findOneBySomeField($value): ?SwShop
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
