<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\MovieHasPeopleDTO;
use App\Repository\MovieHasPeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MovieHasPeopleController extends AbstractController
{
    private MovieHasPeopleRepository $movieHasPeopleRepository;

    public function __construct(MovieHasPeopleRepository $movieHasPeopleRepository)
    {
        $this->movieHasPeopleRepository = $movieHasPeopleRepository;
    }

    /**
     * @Route("/movies-has-people/", methods={"GET"})
     */
    public function getMovieHasPeople(Request $request): JsonResponse
    {
        $movie = $request->query->get('movie');
        $people = $request->query->get('people');

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

        return new JsonResponse(['status' => 'New movie created'], Response::HTTP_CREATED);
    }
}