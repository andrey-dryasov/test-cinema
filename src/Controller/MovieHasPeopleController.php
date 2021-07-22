<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\MovieHasPeopleDTO;
use App\Repository\MovieHasPeopleRepository;
use App\Repository\MovieRepository;
use App\Repository\PeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MovieHasPeopleController extends AbstractController
{
    private MovieHasPeopleRepository $movieHasPeopleRepository;

    private MovieRepository $movieRepository;

    private PeopleRepository $peopleRepository;

    public function __construct(
        MovieHasPeopleRepository $movieHasPeopleRepository,
        MovieRepository $movieRepository,
        PeopleRepository $peopleRepository
    ) {
        $this->movieHasPeopleRepository = $movieHasPeopleRepository;
        $this->movieRepository = $movieRepository;
        $this->peopleRepository = $peopleRepository;
    }

    /**
     * @Route("/movies-has-people/", methods={"GET"})
     */
    public function getMovieHasPeople(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $movie = $data['movie'];
        $people = $data['people'];

        $movieHasPeoples = $this->movieHasPeopleRepository->findBy(['movie' => $movie, 'people' => $people]);

        if (empty($movieHasPeoples)) {
            throw new NotFoundHttpException('MovieHasPeople does not exist');
        }

        $data = [];

        foreach ($movieHasPeoples as $movieHasPeople) {
            $data[] = new MovieHasPeopleDTO($movieHasPeople);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/movies-has-people/", methods={"POST"})
     */
    public function createMovieHasPeople(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'];
        $peopleId = $data['people'];
        $role = $data['role'];
        $significance = $data['significance'] ?? null;

        if (empty($movieId) || empty($peopleId) || empty($role)) {
            throw new NotFoundHttpException('Check params');
        }

        $movie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $people = $this->peopleRepository->findOneBy(['id' => $peopleId]);

        $this->movieHasPeopleRepository->createMovieHasPeople($movie, $people, $role, $significance);

        return new JsonResponse(['status' => 'New MovieHasPeople created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies-has-people/", methods={"PUT"})
     */
    public function updateMovieHasPeople(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'] ?? null;
        $peopleId = $data['people'] ?? null;
        $role = $data['role'] ?? null;
        $significance = $data['significance'] ?? null;

        if (empty($movieId) || empty($peopleId) || empty($role)) {
            throw new NotFoundHttpException('Check params');
        }

        $movieHasPeople = $this->movieHasPeopleRepository->findOneBy(
            [
                'movie' => $movieId,
                'people' => $peopleId,
            ]
        );

        if (!$movieHasPeople) {
            throw new NotFoundHttpException('MovieHasPeople does not exist');
        }

        $movie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $people = $this->peopleRepository->findOneBy(['id' => $peopleId]);

        $this->movieHasPeopleRepository->updateMovieHasPeople($movieHasPeople, $movie, $people, $role, $significance);

        $status = 'MovieHasPeople with movie ' . $movie->getTitle() . ' and ' . $people->getLastName(
            ) . ' ' . $people->getFirstname() . ' was updated';

        return new JsonResponse(['status' => $status], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies-has-people/", methods={"DELETE"})
     */
    public function deleteMovieHasPeople(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'] ?? null;
        $peopleId = $data['people'] ?? null;

        $movieHasPeople = $this->movieHasPeopleRepository->findOneBy(
            [
                'movie' => $movieId,
                'people' => $peopleId,
            ]
        );

        if (!$movieHasPeople) {
            throw new NotFoundHttpException('MovieHasPeople does not exist');
        }

        $movie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $people = $this->peopleRepository->findOneBy(['id' => $peopleId]);
        $status = 'MovieHasPeople with movie ' . $movie->getTitle() . ' and ' . $people->getLastName(
            ) . ' ' . $people->getFirstname() . ' was removed';

        $this->movieHasPeopleRepository->removeMovieHasPeople($movieHasPeople);

        return new JsonResponse(['status' => $status], Response::HTTP_CREATED);
    }
}