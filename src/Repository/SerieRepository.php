<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function findWithJoin(array $criterias, int $limit, int $offset): Paginator
    {
        $q = $this->createQueryBuilder('s')
            ->addSelect('seasons')
            ->leftJoin('s.seasons', 'seasons')
        ;

        foreach($criterias as $criteria => $value) {
            $q->andWhere('s.'.$criteria . ' = \'' . $value. '\'');
        }

        $query =  $q->orderBy('s.firstAirDate', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult(($offset - 1) * $limit)
            ->getQuery();

        return new Paginator($query, true);
    }


    public function getSeriesByDql(float $popularity): array
    {
        $dql = "SELECT s FROM App\Entity\Serie s WHERE s.popularity > :popularity ORDER BY s.popularity";

        return $this->entityManager->createQuery($dql)
            ->setParameter('popularity', $popularity)
            ->execute();
    }

    public function getSeriesBySql(float $popularity): array
    {
        $rawSql = "SELECT * FROM serie s WHERE s.popularity > :popularity";
        $conn = $this->getEntityManager()->getConnection();
        return $conn->prepare($rawSql)->executeQuery(['popularity' => $popularity])->fetchAllAssociative();
    }


    public function getByDateOptionnel(string $date = null): array
    {
        // Fomat date = '2023-10-04'

        $q = $this->createQueryBuilder('w');

        if ($date) {
            $q->andWhere('w.dateCreated = :date')
                ->setParameter('date', $date);
        }

        return $q->orderBy('w.dateCreated', 'DESC')
        ->getQuery()
        ->getResult();
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
