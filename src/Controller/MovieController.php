<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\MovieDTO;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @Route("/movies/{id}", methods={"GET"})
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->movieRepository->findOneBy(['id' => $id]);

        if (!$movie) {
            throw new NotFoundHttpException('Movie does not exist');
        }

        $data = new MovieDTO($movie);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/movies/", methods={"POST"})
     */
    public function createMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'];
        $duration = $data['duration'];

        if (empty($title) || empty($duration)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->movieRepository->createMovie($title, $duration);

        return new JsonResponse(['status' => 'New movie was created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies/{id}", methods={"PUT"})
     */
    public function updateMovie($id, Request $request): JsonResponse
    {
        $movie = $this->movieRepository->findOneBy(['id' => $id]);

        if (!$movie) {
            throw new NotFoundHttpException('Movie does not exist');
        }

        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? null;
        $duration = $data['duration'] ?? null;

        if (empty($title) && empty($duration)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->movieRepository->updateMovie($movie, $title, $duration);

        return new JsonResponse(['status' => 'Movie ' . $movie->getId() . ' was updated'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies/{id}", methods={"DELETE"})
     */
    public function deleteMovie($id): JsonResponse
    {
        $movie = $this->movieRepository->findOneBy(['id' => $id]);

        if (!$movie) {
            throw new NotFoundHttpException('Movie does not exist');
        }

        $movieId = $movie->getId();

        $this->movieRepository->removeMovie($movie);

        return new JsonResponse(['status' => 'Movie ' . $movieId . ' was removed'], Response::HTTP_CREATED);
    }
}