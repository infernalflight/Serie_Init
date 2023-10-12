<?php

namespace App\Helper;

use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;

class Updater
{
    public function __construct(private SerieRepository $serieRepository, private EntityManagerInterface $em){}

    public function removeOldSeries (string $genre = "", bool $force = false): int
    {
        $q = $this->serieRepository->createQueryBuilder('s')
        ->andWhere('s.lastAirDate < :date')
        ->setParameter('date', new \DateTime("-5 years"));

        if ($genre) {
            $q->andWhere($q->expr()->like('s.genres', ':genre'))
                ->setParameter('genre', '%'.$genre.'%');
        }
        $results = $q->getQuery()->getResult();

        $cpt = count($results);

        if ($force) {
            foreach($results as $serie) {
                $this->em->remove($serie);
            }
            $this->em->flush();
        }

        return $cpt;
    }

}