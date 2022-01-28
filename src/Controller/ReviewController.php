<?php

namespace App\Controller;

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
    public function add(Request $request, EntityManagerInterface $manager): Response
    {

        $review = new Review;

        //Création formulaire
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
            
        return $this->renderForm('review/form.html.twig', [
            'title' => 'Critiquez !',
            'form' => $form
        ]);
    }
}
