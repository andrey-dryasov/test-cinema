<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\PeopleDTO;
use App\Repository\PeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PeopleController extends AbstractController
{
    private PeopleRepository $peopleRepository;

    public function __construct(PeopleRepository $peopleRepository)
    {
        $this->peopleRepository = $peopleRepository;
    }

    /**
     * @Route("/peoples/{id}", methods={"GET"})
     */
    public function getPeople(int $id): JsonResponse
    {
        $people = $this->peopleRepository->findOneBy(['id' => $id]);

        if (!$people) {
            throw new NotFoundHttpException('People does not exist');
        }

        $data = new PeopleDTO($people);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/peoples/", methods={"POST"})
     */
    public function createMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $date = $data['date_of_birthday'];
        $nationality = $data['nationality'];

        if (empty($firstname) || empty($lastname) || empty($date) || empty($nationality)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->peopleRepository->createPeople($firstname, $lastname, $date, $nationality);

        return new JsonResponse(['status' => 'New people created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/peoples/{id}", methods={"PUT"})
     */
    public function updateMovie($id, Request $request): JsonResponse
    {
        $people = $this->peopleRepository->findOneBy(['id' => $id]);

        if (!$people) {
            throw new NotFoundHttpException('People does not exist');
        }

        $data = json_decode($request->getContent(), true);

        $firstname = $data['firstname'] ?? null;
        $lastname = $data['lastname'] ?? null;
        $dateOfBirth = $data['date_of_birthday'] ?? null;
        $nationality = $data['nationality'] ?? null;

        if (empty($firstname) && empty($lastname) && empty($dateOfBirth) && empty($nationality)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->peopleRepository->updatePeople($people, $firstname, $lastname, $dateOfBirth, $nationality);

        return new JsonResponse(['status' => 'People ' . $people->getId() . ' was updated'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/peoples/{id}", methods={"DELETE"})
     */
    public function deletePeople($id): JsonResponse
    {
        $people = $this->peopleRepository->findOneBy(['id' => $id]);

        if (!$people) {
            throw new NotFoundHttpException('People does not exist');
        }

        $peopleId = $people->getId();

        $this->peopleRepository->removePeople($people);

        return new JsonResponse(['status' => 'People ' . $peopleId . ' was removed'], Response::HTTP_CREATED);
    }
}