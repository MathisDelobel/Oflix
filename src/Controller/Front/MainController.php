<?php

namespace App\Controller\Front;

use App\Repository\ShowRepository;
use App\Utils\Data;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(ShowRepository $showRepository, Request $request): Response
    {
        // récupérer les données
        $allMovies = $showRepository->findAll();
        
        $nbElementsByPage = 3;
        $pageNumber = $request->query->get('page', 1);
        $nbShowsMax = 8;
        $maxPageCount = ceil($nbShowsMax / $nbElementsByPage);
        if ($pageNumber > $maxPageCount) {
            $pageNumber = $maxPageCount;
        }
        if ($pageNumber < 1) {
            $pageNumber = 1;
        }
        $bestRatedMovies = $showRepository->findByRatingOver(4.5, $nbElementsByPage, $pageNumber);
        // récupérer les films en session
        // pour chaque movie, vérifier si il est en session
        // si oui ajouter l'id dans un tableau
        //fournir le tableau des movies qui sont en session

        // les fournir à la vue
        return $this->render('main/home.html.twig', [
            'movieList' => $bestRatedMovies,
            'selectedPage' => $pageNumber,
            'maxPageCount' => $maxPageCount
        ]);
    }
}