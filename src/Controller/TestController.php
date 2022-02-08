<?php

namespace App\Controller;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(OmdbApi $omdbApi): Response
    {
        $results = $omdbApi->fetchPoster('men in black');
        return $this->render('test/index.html.twig', [
            'results' => $results
        ]);
    }
}
