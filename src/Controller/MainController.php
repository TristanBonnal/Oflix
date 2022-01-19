<?php
namespace App\Controller;

use App\Models\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil
     * 
     *@Route("/", name="home")

     * @return Response
     */
    public function home(): Response
    {
        dump('bonjour');
        $movieModel = new Movie();
        $movies = $movieModel->getAllMovies();
        return $this->render('main/index.html.twig', [
            'title' => 'Oflix',
            'movies' => $movies
        ]);
    }
}