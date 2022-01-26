<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        //Genre
        $genres = [
            'Action',
            'Aventure',
            'Amour',
            'Comédie',
            'Horreur',
            'Drame',
            'Science-fiction',
            'Space-opéra',
            'Historique',
            'Documentaire',
            'Animé',
            'Peplum',
            'Erotique',
        ];
        foreach ($genres as $genre) {
            $newGenre = new Genre;
            $newGenre->setName($genre);
            $manager->persist($newGenre);
        }

        for ($i = 1; $i<= 1; $i++) {
            //Movies
            $newMovie =  new Movie();
            $newMovie->setTitle(ucfirst($faker->word()));
            $newMovie->setDuration(rand(30, 180));
            $newMovie->setRating(mt_rand(0, 50) / 10);
            $type = rand(1, 2) == 1 ? 'Film' : 'Série';
            $newMovie->setType($type);
            $newMovie->setReleaseDate(new \DateTime("now"));
            $newMovie->setSummary($faker->paragraph(1));
            $newMovie->setSynopsis($faker->paragraph(3));
            $newMovie->setPoster('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');
            for ($i = 1; $i <= mt_rand(1, 2); $i++) {
                $genreToAdd = new Genre;
                $genreToAdd->setName($genres[mt_rand(0, 12)]);
                $newMovie->addGenre($genreToAdd);
            }

            //Seasons 
            if ($type == 'Série')
            {
                $nbSeason = rand(0, 5); 
                for ($j = 1; $j <= $nbSeason; $j++ ) 
                {
                    $newSeason = new Season();
                    $newSeason->setNumber($j);
                    $newSeason->setEpisodesNumber(mt_rand(6, 24));
                    
                    $manager->persist($newSeason);
                    
                    $newMovie->addSeason($newSeason);
                }
            }
            $manager->persist($newMovie);
        }



        //Actor
        for ($i = 1; $i<= 10; $i++) {
            $newActor = new Actor;

            $newActor->setFirstname($faker->firstName())
                     ->setLastname($faker->lastName());

            $manager->persist($newActor);
        }

        $manager->flush();
    }
}
