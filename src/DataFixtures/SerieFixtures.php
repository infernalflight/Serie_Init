<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

;

class SerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i =0; $i<100; $i++) {
            $serie = new Serie();

            $serie->setName($faker->words(5, true));
            $serie->setOverview($faker->words(50, true));
            $serie->setFirstAirDate($faker->dateTimeBetween(new \DateTime('-5 year'), new \DateTime()));
            $serie->setPopularity($faker->numberBetween(0, 1000));
            $serie->setVote($faker->numberBetween(0, 10));
            $serie->setStatus($faker->randomElement(['Canceled', 'ended', 'returning']));

            if ($serie->getStatus() !== 'returning') {
                $serie->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate(), new \DateTime()));
            }

            $serie->setGenres($faker->randomElement(['SF', 'comedy', 'drama', 'western']));
            $serie->setBackdrop('backdrop.jpg');
            $serie->setPoster('poster.jpg');

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
