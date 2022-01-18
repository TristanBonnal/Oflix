<?php
namespace App\Controller;

use App\Models\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * Affiche la page détaillée d'un film
     * 
     *@Route("/movies/{id}", name="movie")

     * @return Response
     */
    public function getMovie($id): Response
    {
        $movie = new Movie();
        $movie = $movie->find($id);
        return $this->render('movies/details.html.twig', [
            'title' => $movie['title'],
            'movie' => $movie
        ]);
    }

    /**
     * Affiche la page de favoris
     * 
     *@Route("/favorites", name="favorites")

     * @return Response
     */
    public function getFavorites(): Response
    {
        $movieModel = new Movie();
        $movies = $movieModel->getAllMovies();
        return $this->render('movies/list.html.twig', [
            'title' => 'Favoris',
            'movies' => $movies
        ]);
    }


}