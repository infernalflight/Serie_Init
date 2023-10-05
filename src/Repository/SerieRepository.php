<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct($registry, Serie::class);
    }

    public function findBestSeries(float $popularity, float $vote): array
    {
        $q = $this->createQueryBuilder('s');
        $q->andWhere('s.popularity > :popularity')
            ->setParameter('popularity', $popularity);

        $expr = $q->expr();
        $cond1 = $expr->between('s.firstAirDate', ':min', ':max');
        $cond2 = $expr->like('s.name', ':lettre');

        $cond3 = $expr->gt('s.popularity', 300);


        $q->andWhere($expr->orX($cond1, $cond2))
            ->setParameter('min', '2018-01-01')
            ->setParameter('max', '2020-12-31')
            ->setParameter('lettre', '%b%');

        return $q->getQuery()
            ->getResult();
    }

    public function getSeriesByDql(float $popularity): array
    {
        $dql = "SELECT s FROM App\Entity\Serie s WHERE s.popularity > :popularity ORDER BY s.popularity";

        return $this->entityManager->createQuery($dql)
            ->setParameter('popularity', $popularity)
            ->execute();
    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
