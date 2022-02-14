<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Models\JsonError;
use App\Models\Movies;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_list_movies", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository): Response
    {
        // on renvoit une réponse de type JsonResponse
        return $this->json(
            $movieRepository->findAll(),
            200,
            // HTTP headers supplémentaires, dans notre cas : aucune
            [],
            // Contexte de serialisation, les groups de propriété que l'on veux serialise
            ['groups'=> 'list_movie']
        );
    }

    /**
     * @Route("/api/genres", name="api_list_genres", methods={"GET"})
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
     * @Route("/api/genres/{id}/movies", name="api_list_movies_by_genre")
     */
    public function moviesByGenre(Genre $genre = null): Response
    {
        if (!$genre) {
            $error = new JsonError(Response::HTTP_NOT_FOUND, 'Genre non trouvé');
            return $this->json($error, $error->getError());
        }
        return $this->json(
            $genre->getMovies(),
            200,
            [],
            ['groups'=> 'list_movie']
        );
    }

    /**
     * @Route("/api/movies/random", name="api_movie_random")
     */
    public function randomMovie(MovieRepository $repo): Response
    {
        return $this->json(
            $repo->findRandomMovie(),
            200,
            [],
            ['groups'=> 'list_movie']
        );
    }

     /**
     * @Route("/api/movies", name="api_movies_create", methods={"POST"})
     */
    public function createMovie(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator) : Response
    {
        //Récupération du corps de la requete
        $data = $request->getContent();

        //Conversion JSON en objet Movie
        try {
            $newMovie =  $serializer->deserialize($data, Movie::class, 'json');
        } catch (Exception $e) {
            
            return new JsonResponse('JSON Invalide', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
     
        //Validation avant insertion
        $errors = $validator->validate($newMovie);
        if (count($errors) > 0) {
            $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
            $myJsonError->setValidationErrors($errors);
            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $doctrine->persist($newMovie);
        $doctrine->flush();
        return $this->json(
            $newMovie,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['list_movie']]
        );
    }

    
    /**
     * @Route("/api/secure/genres", name="api_genres_create", methods={"POST"})
     * @link https://symfony.com/doc/current/validation.html#using-the-validator-service
     */
    public function createGenre(EntityManagerInterface $doctrine, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $data = $request->getContent();
        try { 
            $newgenre =  $serializer->deserialize($data, Genre::class, 'json');
        } catch (Exception $e){ 

            return new JsonResponse("Hoouuu !! Ce qui vient d'arriver est de votre faute : JSON invalide", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        //@link : https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($newgenre);
        if (count($errors) > 0) {
            //dd($errors);
            $myJsonError = new JsonError(Response::HTTP_UNPROCESSABLE_ENTITY, "Des erreurs de validation ont été trouvées");
            $myJsonError->setValidationErrors($errors);


            //$errorsString = (string) $errors;
    
            return $this->json($myJsonError, $myJsonError->getError());
        }
        
        $doctrine->persist($newgenre);
        $doctrine->flush();

        return $this->json(
            $newgenre,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['show_genre']]
        );
    }
} 




