<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\MovieHasType;
use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MovieHasTypeRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, MovieHasType::class);
        $this->manager = $manager;
    }

    public function createMovieHasType(Movie $movie, Type $type): void
    {
        $movieHasType = new MovieHasType();

        $movieHasType
            ->setMovie($movie)
            ->setType($type);

        $this->manager->persist($movieHasType);
        $this->manager->flush();
    }

    public function updateMovieHasType(MovieHasType $movieHasType, ?Movie $movie, ?Type $type): void
    {
        if ($movie) {
            $movieHasType->setMovie($movie);
        }

        if ($type) {
            $movieHasType->setType($type);
        }

        $this->manager->persist($movieHasType);
        $this->manager->flush();
    }

    public function removeMovieHasType(MovieHasType $movieHasType): void
    {
        $this->manager->remove($movieHasType);
        $this->manager->flush();
    }
}