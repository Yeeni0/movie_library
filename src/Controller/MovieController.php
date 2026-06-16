<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie')]
final class MovieController extends AbstractController
{
    // Page de liste des films et recherche par titre.
    #[Route(name: 'app_movie_index', methods: ['GET'])]
    public function index(Request $request, MovieRepository $movieRepository): Response
    {
        // On récupère le paramètre q dans l'URL pour la recherche.
        $search = trim((string) $request->query->get('q', ''));

        // Si l'utilisateur a saisi une recherche, on filtre par titre.
        // Sinon on récupère tous les films.
        $movies = $search !== ''
            ? $movieRepository->findByTitleSearch($search)
            : $movieRepository->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'search' => $search,
        ]);
    }

    // Création d'un nouveau film.
    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $movie = new Movie();

        // Le formulaire est lié à l'entité Movie.
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lors de la soumission valide, on enregistre le film en base.
            $entityManager->persist($movie);
            $entityManager->flush();

            // Redirection vers la liste pour éviter la double soumission.
            return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    // Affichage d'un film unique.
    #[Route('/{id}', name: 'app_movie_show', methods: ['GET'])]
    public function show(Movie $movie): Response
    {
        // Le Movie est injecté automatiquement grâce à la conversion de paramètres.
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    // Édition d'un film existant.
    #[Route('/{id}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le film existe déjà, on met juste à jour les changements.
            $entityManager->flush();

            return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    // Suppression d'un film.
    #[Route('/{id}', name: 'app_movie_delete', methods: ['POST'])]
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        // Vérifie le token CSRF pour sécuriser la suppression.
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
