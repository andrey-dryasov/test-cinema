<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\Request\MovieCreateRequestDTO;
use App\DTO\Request\MovieUpdateRequestDTO;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovieRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Movie::class);
        $this->manager = $manager;
    }

    public function findMovie(int $id): Movie
    {
        $movie = $this->findOneBy(['id' => $id]);

        if (!$movie) {
            throw new NotFoundHttpException('Movie does not exist');
        }

        return $movie;
    }

    public function createMovie(MovieCreateRequestDTO $movieCreateRequestDTO): void
    {
        $movie = new Movie();

        $movie
            ->setTitle($movieCreateRequestDTO->title)
            ->setDuration($movieCreateRequestDTO->duration);

        $this->manager->persist($movie);
        $this->manager->flush();
    }

    public function updateMovie(Movie $movie, MovieUpdateRequestDTO $movieUpdateRequestDTO): void
    {
        if ($movieUpdateRequestDTO->title) {
            $movie->setTitle($movieUpdateRequestDTO->title);
        }

        if ($movieUpdateRequestDTO->duration) {
            $movie->setDuration($movieUpdateRequestDTO->duration);
        }

        $this->manager->persist($movie);
        $this->manager->flush();
    }

    public function removeMovie(Movie $movie): void
    {
        $this->manager->remove($movie);
        $this->manager->flush();
    }

    public function getMoviesForImdbInfo(int $limit, array $excludedMovies): array
    {
        $queryBuilder = $this->createQueryBuilder('m');

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->isNull('m.posterUrl'),
            )
            ->orderBy('m.id')
            ->setMaxResults($limit);

        if (!empty($excludedMovies)) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->notIn('m.id', ':excludedMovies')
                )
                ->setParameter('excludedMovies', $excludedMovies);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}