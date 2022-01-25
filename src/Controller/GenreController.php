<?php

namespace App\Controller;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre/{id}", name="genre", requirements={"id": "\d+"})
     */
    public function index(Genre $genre): Response
    {
        // comme je demande un Genre, et qu'il y a un {id} dans la route
        // Le framework va automatiquement faire un find avec l'id

        return $this->render('genre/index.html.twig', [
            'genre' => $genre
        ]);
    }
}
