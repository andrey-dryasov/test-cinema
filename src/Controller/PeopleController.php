<?php
declare(strict_types=1);

namespace App\Controller;

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

        $data = [
            'id' => $people->getId(),
            'firstname' => $people->getFirstname(),
            'lastname' => $people->getLastname(),
            'date_of_birthday' => ($people->getDateOfBirthday())->format('Y-m-d'),
            'nationality' => $people->getNationality(),
        ];

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
}