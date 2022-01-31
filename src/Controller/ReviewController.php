<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/new/{id}", name="review_new")
     */
    public function add(Request $request, EntityManagerInterface $manager, Movie $movie): Response
    {
        
        $review = new Review;

        //Création formulaire
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        //Redirection après validation du form
        if ($form->isSubmitted() && $form->isValid()) {
            $review->setMovie($movie);
            $manager->persist($review);
            $manager->flush();

            return $this->redirectToRoute('movie@', ['id' => $movie->getId()]);
        }

            
        return $this->renderForm('review/form.html.twig', [
            'title' => 'Critiquez !',
            'form' => $form
        ]);
    }
}
