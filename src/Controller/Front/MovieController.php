<?php

namespace App\Controller\Front;

use App\Repository\CastingRepository;
use App\Repository\ShowRepository;
use App\Utils\Data;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie', name: 'app_movie_')]
class MovieController extends AbstractController
{

    #[Route('/favorites', name: 'favorites', methods: ['GET'])]
    public function favorites(Request $req): Response
    {
        // récupérer les données 
        // ici les films dans la session
        $session = $req->getSession();
        $favoriteMovies = $session->get('favorite_movies', []);

        //todo dynamiser la page favorites
        return $this->render('movie/favorites.html.twig', [
            'movieList' => $favoriteMovies
        ]);
    }

    #[Route('/favorites/{id<\d+>}', name: 'favorites_add', methods: ['GET'])]
    public function favoritesAdd(int $id, Request $request, ShowRepository $showRepository): Response
    {
        // récupérer l'id de l'url done !
        // récupérer le movie qui correspond à l'id
        $movie = $showRepository->find($id);

      

        // ajouter le movie dans la session
        $session = $request->getSession();
        // récupérer les éventuels movies en sessions dans une variable $moviesInSession
        $moviesInSession = $session->get('favorite_movies', []);

        // ajouter le movie actuel dans le tableau $moviesInSession
        $moviesInSession[$id] = $movie;

        // réécrire le tableau dans la session ( à la meme clef )
        $session->set('favorite_movies', $moviesInSession);

        $this->addFlash('success', 'Le film ' . $movie->getTitle() . ' a été ajouté avec succès');

        // rediriger l'utilisateur sur la page /favorites
        return $this->redirectToRoute('app_movie_favorites');
    }

    #[Route('/favorites/remove/{id<\d+>}', name: 'favorites_remove', methods: ['GET'])]
    public function favoritesRemove(int $id, Request $request): Response
    {


        $session = $request->getSession();
        // récupérer les éventuels movies en sessions dans une variable $moviesInSession
        $moviesInSession = $session->get('favorite_movies', []);

        // supprimer le film du tableau des favoris
        unset($moviesInSession[$id]);
        // todo afficher le nom du film dans le message flash
        $this->addFlash('success', 'Le film a été supprimé de vos favoris');

        // réécrire le tableau dans la session ( à la meme clef )
        $session->set('favorite_movies', $moviesInSession);


        // rediriger l'utilisateur sur la page /favorites
        return $this->redirectToRoute('app_movie_favorites');
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(ShowRepository $showRepository): Response
    {
        $allMovies = $showRepository->findAll();

        return $this->render('movie/list.html.twig', [
            'movieList' => $allMovies
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, CastingRepository $castingRepository, ShowRepository $showRepository): Response
    {
        $movie = $showRepository->findOneWithCastingsAndPersons($id);

        if (empty($movie)) {
            // le film n'existe pas en BDD
            // 404
            throw $this->createNotFoundException('Le film demandé n\'existe pas');
        }
        ;

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            // 'castingList' => $castingList,
        ]);
    }

    
}