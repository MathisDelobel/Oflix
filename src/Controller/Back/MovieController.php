<?php

namespace App\Controller\Back;

use App\Entity\Show;
use App\Form\ShowType;
use App\Repository\ShowRepository;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/back/movie')]
#[IsGranted('BACK_OFFICE_ACCESS')]
class MovieController extends AbstractController
{
    #[Route('/', name: 'app_back_movie_index', methods: ['GET'])]

    public function index(ShowRepository $showRepository): Response
    {

        return $this->render('back/movie/index.html.twig', [
            'shows' => $showRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_back_movie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($show);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/movie/new.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_movie_show', methods: ['GET'])]
    public function show(Show $show): Response
    {
        return $this->render('back/movie/show.html.twig', [
            'show' => $show,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Show $show, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/movie/edit.html.twig', [
            'show' => $show,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_back_movie_delete', methods: ['POST'])]
    public function delete(Request $request, Show $show, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $show->getId(), $request->request->get('_token'))) {
            $entityManager->remove($show);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_back_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}