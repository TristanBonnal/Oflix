<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{slug}", name="movie")
     */
    public function show(MovieRepository $movieRepo, $slug, Movie $movie): Response
    {
        return $this->render('movie/details.html.twig', [
            'title' => $movie->getTitle(),
            'movie' => $movie,
            'castings' => $movie->getCastings()
        ]);
    }

    /**
     * @Route("/movies", name="movies")
     */
    public function showAll(MovieRepository $movieRepos): Response
    {
        $movies = $movieRepos->findAll();
        dump($movies);


        return $this->render('movie/list.html.twig', [
            'title' => 'Liste',
            'movies' => $movies
        ]);
    }

    /**
     * methode de creation
     *
     * @param EntityManagerInterface $doctrine 
     * @Route("/movie/create", name="movie_create")
     */
    public function create(EntityManagerInterface $doctrine): Response
    {
        $newMovie =  new Movie();
        $newMovie->setTitle("Mon voisin totoro III : la vengeance");
        $newMovie->setDuration(90);
        $newMovie->setType("Film");
        $newMovie->setReleaseDate(new DateTime("now"));
        $newMovie->setSummary("lorem");
        $newMovie->setSynopsis("lorem ipsum");
        $newMovie->setPoster("https://m.media-amazon.com/images/M/MV5BYzJjMTYyMjQtZDI0My00ZjE2LTkyNGYtOTllNGQxNDMyZjE0XkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg");

        $doctrine->persist($newMovie);
        $doctrine->flush();
        return $this->redirectToRoute("movies");
    }

    /**
     * Movie update
     * 
     * @Route("/movie/update/{id}", requirements={"id"="\d+"})
     */
    public function update(int $id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        $movie = $movieRepository->find($id);

        $movie->setTitle('Avatar ' . mt_rand(2, 99));

        $doctrine->flush();

        return $this->redirectToRoute("movies");
    }

    /**
     * Movie delete
     * 
     * @Route("/movie/delete/{id}", requirements={"id"="\d+"})
     */
    public function delete($id, MovieRepository $movieRepository, EntityManagerInterface $doctrine)
    {
        $movie = $movieRepository->find($id);

        $doctrine->remove($movie);

        $doctrine->flush();

        return $this->redirectToRoute("movies");
    }
}
