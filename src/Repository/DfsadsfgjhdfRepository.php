<?php

namespace App\Repository;

use App\Entity\Dfsadsfgjhdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dfsadsfgjhdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dfsadsfgjhdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dfsadsfgjhdf[]    findAll()
 * @method Dfsadsfgjhdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DfsadsfgjhdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dfsadsfgjhdf::class);
    }

    // /**
    //  * @return Dfsadsfgjhdf[] Returns an array of Dfsadsfgjhdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dfsadsfgjhdf
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
