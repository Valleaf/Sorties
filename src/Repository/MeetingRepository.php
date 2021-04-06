<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Meeting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Meeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meeting[]    findAll()
 * @method Meeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry , PaginatorInterface $paginator)
    {
        parent::__construct($registry, Meeting::class);
        $this->paginator = $paginator;
    }

    public function findActive(SearchData $search,
                               User $user): Paginator
    {

        $currentTime = new \DateTime();
        $currentTime->modify('- 30 days');
        $timeOneMonth = $currentTime->format('Y-m-d');
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder
            ->select('m',
                'mo',
                'oc',
                'mp',
                'mpc',
                's',
                'p')
            ->innerJoin('m.organisedBy','mo')
            ->join('mo.campus','oc')
            ->innerJoin('m.place','mp')
            ->join('mp.city','mpc')
            ->join('m.status','s')
            ->leftJoin('m.participants','p') //Reduit le nombre de requete, mais fait buguer au niveau de la recherche-
            ->andWhere('m.timeStarting >= :timeOneMonth')
            ->setParameter('timeOneMonth',"$timeOneMonth")
            ->orderBy('m.timeStarting')
        ;
        if(!empty($search->q)) {
            $queryBuilder = $queryBuilder
                ->andWhere('m.name LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }
        if(!empty($search->min)){
            $queryBuilder = $queryBuilder
                ->andWhere('m.timeStarting >= :min')
                ->setParameter('min',"{$search->min->format('Y-m-d')}");
        }
        if(!empty($search->max)){
            $queryBuilder = $queryBuilder
                ->andWhere('m.timeStarting <= :max')
                ->setParameter('max',"{$search->max->format('Y-m-d')}");
        }
        if(!empty($search->isOrganizedBy)){
            $queryBuilder = $queryBuilder
                ->andWhere('m.organisedBy = '.$user->getId());
        }

        if(empty($search->isOver)){
            $queryBuilder = $queryBuilder
                ->andWhere(" DATE_ADD(m.timeStarting,m.length, 'minute')  >= CURRENT_DATE()");
        }

        if(!empty($search->isRegisteredTo)){
        }
        if(!empty($search->isNotRegisteredTo)){
        }
        if(!empty($search->campus)){
            $queryBuilder=$queryBuilder
                ->andWhere('m.campus = :campus')
                ->setParameter('campus',"{$search->campus->getId()}");
        }




        $query = $queryBuilder->getQuery();
        //return $this->paginator->paginate($query,1,3);
        return new Paginator($query);
    }



    // /**
    //  * @return Meeting[] Returns an array of Meeting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Meeting
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
