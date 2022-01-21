<?php
namespace App\Controller;

use App\Models\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class MovieController extends AbstractController
{
    /**
     * Affiche la page détaillée d'un film
     * 
     *@Route("/movies/{id}", name="movie", requirements={"id"="\d+"})

     * @return Response
     */
    public function getMovie($id): Response
    {
        $movie = new Movie();

        if (!array_key_exists($id, $movie->getAllMovies())) {
            throw new NotFoundHttpException('Ce film n\'existe pas');
        }

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