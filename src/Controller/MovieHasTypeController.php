<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\MovieHasTypeDTO;
use App\Repository\MovieHasTypeRepository;
use App\Repository\MovieRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MovieHasTypeController extends AbstractController
{
    private MovieHasTypeRepository $movieHasTypeRepository;

    private MovieRepository $movieRepository;

    private TypeRepository $typeRepository;

    public function __construct(
        MovieHasTypeRepository $movieHasTypeRepository,
        MovieRepository $movieRepository,
        TypeRepository $typeRepository
    ) {
        $this->movieHasTypeRepository = $movieHasTypeRepository;
        $this->movieRepository = $movieRepository;
        $this->typeRepository = $typeRepository;
    }

    /**
     * @Route("/movies-has-type/", methods={"GET"})
     */
    public function getMovieHasType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $movie = $data['movie'];
        $type = $data['type'];

        $movieHasTypes = $this->movieHasTypeRepository->findBy(['movie' => $movie, 'type' => $type]);

        if (empty($movieHasTypes)) {
            throw new NotFoundHttpException('MovieHasType does not exist');
        }

        $data = [];

        foreach ($movieHasTypes as $movieHasType) {
            $data[] = new MovieHasTypeDTO($movieHasType);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/movies-has-type/", methods={"POST"})
     */
    public function createMovieHasType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'];
        $typeId = $data['type'];

        if (empty($movieId) || empty($typeId)) {
            throw new NotFoundHttpException('Check params');
        }

        $movie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $type = $this->typeRepository->findOneBy(['id' => $typeId]);

        $this->movieHasTypeRepository->createMovieHasType($movie, $type);

        return new JsonResponse(['status' => 'New movieHasType created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies-has-type/", methods={"PUT"})
     */
    public function updateMovieHasType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'] ?? null;
        $typeId = $data['type'] ?? null;
        $newMovieId = $data['new_movie'] ?? null;
        $newTypeId = $data['new_type'] ?? null;

        if (empty($movieId) || empty($typeId)) {
            throw new NotFoundHttpException('Check params');
        }

        $movieHasType = $this->movieHasTypeRepository->findOneBy(
            [
                'movie' => $movieId,
                'type' => $typeId,
            ]
        );

        if (!$movieHasType) {
            throw new NotFoundHttpException('MovieHasType does not exist');
        }

        $oldMovie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $oldType = $this->typeRepository->findOneBy(['id' => $typeId]);
        $newMovie = $this->movieRepository->findOneBy(['id' => $newMovieId]);
        $newType = $this->typeRepository->findOneBy(['id' => $newTypeId]);

        $this->movieHasTypeRepository->updateMovieHasType($movieHasType, $newMovie, $newType);

        $status = 'MovieHasType with movie ' . $oldMovie->getTitle() . ' and ' . $oldType->getName() . ' was updated';

        return new JsonResponse(['status' => $status], Response::HTTP_CREATED);
    }

    /**
     * @Route("/movies-has-type/", methods={"DELETE"})
     */
    public function deleteMovieHasType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $movieId = $data['movie'] ?? null;
        $typeId = $data['type'] ?? null;

        $movieHasType = $this->movieHasTypeRepository->findOneBy(
            [
                'movie' => $movieId,
                'type' => $typeId,
            ]
        );

        if (!$movieHasType) {
            throw new NotFoundHttpException('MovieHasType does not exist');
        }

        $movie = $this->movieRepository->findOneBy(['id' => $movieId]);
        $type = $this->typeRepository->findOneBy(['id' => $typeId]);
        $status = 'MovieHasType with movie ' . $movie->getTitle() . ' and ' . $type->getName() . ' was removed';

        $this->movieHasTypeRepository->removeMovieHasType($movieHasType);

        return new JsonResponse(['status' => $status], Response::HTTP_CREATED);
    }
}