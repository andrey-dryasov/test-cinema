<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\TypeDTO;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/types")
 */
class TypeController extends AbstractController
{
    private TypeRepository $typeRepository;

    public function __construct(TypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getType(int $id): JsonResponse
    {
        $type = $this->typeRepository->findOneBy(['id' => $id]);

        if (!$type) {
            throw new NotFoundHttpException('Type does not exist');
        }

        $data = new TypeDTO($type);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function createType(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];

        if (empty($name)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->typeRepository->createType($name);

        return new JsonResponse(['status' => 'New type created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateType(int $id, Request $request): JsonResponse
    {
        $type = $this->typeRepository->findOneBy(['id' => $id]);

        if (!$type) {
            throw new NotFoundHttpException('Type does not exist');
        }

        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;

        if (empty($name)) {
            throw new NotFoundHttpException('Check params');
        }

        $this->typeRepository->updateType($type, $name);

        return new JsonResponse(['status' => 'Type ' . $type->getId() . ' was updated'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteType(int $id): JsonResponse
    {
        $type = $this->typeRepository->findOneBy(['id' => $id]);

        if (!$type) {
            throw new NotFoundHttpException('Type does not exist');
        }

        $typeId = $type->getId();

        $this->typeRepository->removeType($type);

        return new JsonResponse(['status' => 'Type ' . $typeId . ' was removed'], Response::HTTP_CREATED);
    }
}