<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Entity\Show;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/review', name: 'app_review_', methods: ['GET', 'POST'])]
class ReviewController extends AbstractController
{
    #[Route('/add/{id}', name: 'add')]
    public function add(Request $request, Show $show, EntityManagerInterface $entityManager): Response
    {
        // * symfo fait un find du bon show et le stock dans la variable $show grâce à Show $show

        // on crée l'objet vide car on fait une création
        $review = new Review();

        // on instancie un form grâce à notre type
        $form = $this->createForm(ReviewType::class, $review);

        // ici on fait le lien entre la requete et l'objet form
        $form->handleRequest($request);

        // on conditionne le fait que le form soit bien envoyé et soit bien remplis
        if ($form->isSubmitted() && $form->isValid()) {
            
            $review->setMovie($show);
            // je persiste en bdd
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_movie_show', ["id" => $show->getId()]);
        }

        return $this->render('review/add.html.twig', [
            "form" => $form
        ]);
    }
}