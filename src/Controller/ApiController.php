<?php

namespace App\Controller;

use App\Models\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/movies", name="list")
     */
    public function allMovies(): Response
    {
        $model = new Movie();
        return $this->json($model->getAllMovies(), );
    }
}
