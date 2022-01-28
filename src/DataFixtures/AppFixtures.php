<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\OflixProvider;
use App\Entity\Actor;
use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $connexion;

    public function __construct(Connection $connexion)
    {
        $this->connexion = $connexion;
    }

    public function truncate()
    {
        // On désactive la vérification des FK
        // Sinon les truncate ne fonctionnent pas.
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        //
        $this->connexion->executeQuery('TRUNCATE TABLE casting');
        $this->connexion->executeQuery('TRUNCATE TABLE genre');
        $this->connexion->executeQuery('TRUNCATE TABLE movie');
        $this->connexion->executeQuery('TRUNCATE TABLE movie_genre');
        $this->connexion->executeQuery('TRUNCATE TABLE actor');
        $this->connexion->executeQuery('TRUNCATE TABLE season');

    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();

        $faker = Factory::create('fr_FR');
        $oflixProvider = new OflixProvider();
        //Genres
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
        ];
        $genresObjects = [];
        foreach ($genres as $genre) {
            $newGenre = new Genre;
            $newGenre->setName($genre);
            $genresObjects[] = $newGenre;
            $manager->persist($newGenre);
        }

        //Actors
        $actors = [];
        for ($i = 1; $i<= 50; $i++) {
            $newActor = new Actor;
            $newActor->setFirstname($faker->firstName())
                     ->setLastname($faker->lastName());
            $actors[] = $newActor;
            
            $manager->persist($newActor);
        }

        for ($i = 1; $i<= 20; $i++) {
            //Movies
            $newMovie =  new Movie();
            $newMovie->setTitle(ucfirst($oflixProvider->movieTitle()));
            $newMovie->setDuration(rand(30, 180));
            $newMovie->setRating(mt_rand(0, 50) / 10);
            $type = rand(1, 2) == 1 ? 'Film' : 'Série';
            $newMovie->setType($type);
            $newMovie->setReleaseDate(new \DateTime("now"));
            $newMovie->setSummary($faker->paragraph(1));
            $newMovie->setSynopsis($faker->paragraph(3));
            $newMovie->setPoster('https://picsum.photos/id/'.mt_rand(1, 100).'/303/424');

            //Addgenres
            $nbGenres = rand(0,3);
            for ($j = 1; $j <= $nbGenres; $j++) {
                $newMovie->addGenre($genresObjects[mt_rand(0, 11)]);
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

            //Casting
            $nbCastings = rand(1, 7);
            for ($k = 1; $k<= $nbCastings; $k++) {
                $actorCasted = $actors[mt_rand(0,12)];
                $newCasting = new Casting;
                $newCasting->setRole($faker->firstName())
                            ->setMovie($newMovie)
                            ->setActor($actorCasted)
                            ->setCreditOrder(mt_rand(1,5));
                
                $manager->persist($newCasting);
            }


                $manager->persist($newMovie);
            }

        $manager->flush();
    }
}
