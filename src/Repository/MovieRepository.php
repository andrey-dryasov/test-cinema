<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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

    public function createMovie(string $title, int $duration): void
    {
        $movie = new Movie();

        $movie
            ->setTitle($title)
            ->setDuration($duration);

        $this->manager->persist($movie);
        $this->manager->flush();
    }

    public function updateMovie(Movie $movie, ?string $title, ?int $duration): void
    {
        if ($title) {
            $movie->setTitle($title);
        }

        if ($duration) {
            $movie->setDuration($duration);
        }

        $this->manager->persist($movie);
        $this->manager->flush();
    }

    public function removeMovie(Movie $movie): void
    {
        $this->manager->remove($movie);
        $this->manager->flush();
    }
}