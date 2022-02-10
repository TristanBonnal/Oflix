<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Models\Movies;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_list_movies")
     */
    public function list(MovieRepository $movieRepository): Response
    {
        // on renvoit une réponse de type JsonResponse
        // c'est la même chose que Response, en plus spécifique
        // car ça rajoute le contentType dans les headers
        return $this->json(
            // les données à transformer en JSON
            $movieRepository->findAll(),
            // HTTP STATUS CODE
            200,
            // HTTP headers supplémentaires, dans notre cas : aucune
            [],
            // Contexte de serialisation, les groups de propriété que l'on veux serialise
            ['groups'=> 'list_movie']
        );
    }

    /**
     * @Route("/api/genres", name="api_list_genres")
     */
    public function listGenres(GenreRepository $genreRepository): Response
    {
        return $this->json(
            $genreRepository->findAll(),
            200,
            [],
            ['groups'=> 'list_genre']
        );
    }

    /**
     * @Route("/api/genre/{id}/movies", name="api_list_genres")
     */
    public function moviesByGenre(MovieRepository $repo, Genre $genre): Response
    {
        return $this->json(
            $repo->findMovieByGenre($genre),
            200,
            [],
            ['groups'=> 'list_genre']
        );
    }
}
