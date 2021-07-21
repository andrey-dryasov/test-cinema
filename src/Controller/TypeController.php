<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    private TypeRepository $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * @Route("/types/{id}", methods={"GET"})
     */
    public function getType(int $id): JsonResponse
    {
        $type = $this->typeRepository->findOneBy(['id' => $id]);

        if (!$type) {
            throw new NotFoundHttpException('Type does not exist');
        }

        $data = [
            'id' => $type->getId(),
            'name' => $type->getName(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/types/", methods={"POST"})
     */
    public function createMovie(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];

        if (empty($name)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->typeRepository->createType($name);

        return new JsonResponse(['status' => 'New type created'], Response::HTTP_CREATED);
    }
}