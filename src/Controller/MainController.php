<?php
namespace App\Controller;

use App\Models\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    
    /**
     * changement de theme
     * 
     * @Route("/theme/toggle", name="main_theme_switcher")
     *
     * @return Response
     */
    public function themeSwitcher(SessionInterface $session, Request $request): Response
    {
        // dd($request);

        $theme = $session->get('theme', 'netflix');

        if ($theme === 'netflix'){
            $session->set('theme', 'allocine');
        } else {
            $session->set('theme', 'netflix');
        }
        
        // $routeName = $request->getCurrentRequest();
        // dd($routeName);
        // TODO UX redirect vers la page courante
        return $this->redirectToRoute("main_home");

    }
}