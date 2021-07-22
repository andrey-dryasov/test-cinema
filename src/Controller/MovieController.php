<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\MovieDTO;
use App\DTO\Request\MovieCreateRequestDTO;
use App\DTO\Request\MovieUpdateRequestDTO;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/movies")
 */
class MovieController extends AbstractController
{
    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getMovie(int $id): JsonResponse
    {
        $movie = $this->movieRepository->findMovie($id);
        $data = new MovieDTO($movie);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function createMovie(Request $request): JsonResponse
    {
        $movieCreateRequestDTO = new MovieCreateRequestDTO($request);

        $this->movieRepository->createMovie($movieCreateRequestDTO);

        return new JsonResponse(['status' => 'New movie was created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateMovie($id, Request $request): JsonResponse
    {
        $movie = $this->movieRepository->findMovie($id);
        $movieUpdateRequestDTO = new MovieUpdateRequestDTO($request);

        $this->movieRepository->updateMovie($movie, $movieUpdateRequestDTO);

        return new JsonResponse(['status' => 'Movie ' . $movie->getId() . ' was updated'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteMovie($id): JsonResponse
    {
        $movie = $this->movieRepository->findMovie($id);
        $movieId = $movie->getId();

        $this->movieRepository->removeMovie($movie);

        return new JsonResponse(['status' => 'Movie ' . $movieId . ' was removed'], Response::HTTP_CREATED);
    }
}