<?php
namespace App\Controller;

use App\Models\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil
     * 
     *@Route("/", name="main_home")

     * @return Response
     */
    public function home(MovieRepository $repoMovie, GenreRepository $repoGenre): Response
    {
        $movies = $repoMovie->findAll();
        $genres = $repoGenre->findAll();
        return $this->render('main/index.html.twig', [
            'title' => 'Oflix',
            'movies' => $movies,
            'genres' => $genres
        ]);
    }
    
    /**
     * changement de theme
     * 
     * @Route("/theme/toggle", name="main_theme_switcher")
     *
     * @return Response
     */
    public function themeSwitcher(SessionInterface $session): Response
    {

        $theme = $session->get('theme', 'netflix');

        if ($theme === 'netflix'){
            $session->set('theme', 'allocine');
        } else {
            $session->set('theme', 'netflix');
        }
        
        return $this->redirectToRoute("main_home");

    }
}