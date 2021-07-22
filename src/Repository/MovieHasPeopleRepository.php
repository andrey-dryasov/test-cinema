<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\MovieHasPeople;
use App\Entity\People;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MovieHasPeopleRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, MovieHasPeople::class);
        $this->manager = $manager;
    }

    public function createMovieHasPeople(Movie $movie, People $people, string $role, ?string $significance): void
    {
        $movieHasPeople = new MovieHasPeople();

        $movieHasPeople
            ->setMovie($movie)
            ->setPeople($people)
            ->setRole($role);

        if ($significance) {
            $movieHasPeople->setSignificance($significance);
        }

        $this->manager->persist($movieHasPeople);
        $this->manager->flush();
    }

    public function updateMovieHasPeople(
        MovieHasPeople $movieHasPeople,
        Movie $movie,
        People $people,
        string $role,
        ?string $significance
    ): void {
        $movieHasPeople
            ->setMovie($movie)
            ->setPeople($people)
            ->setRole($role);

        if ($significance) {
            $movieHasPeople->setSignificance($significance);
        }

        $this->manager->persist($movieHasPeople);
        $this->manager->flush();
    }

    public function removeMovieHasPeople(MovieHasPeople $movieHasPeople): void
    {
        $this->manager->remove($movieHasPeople);
        $this->manager->flush();
    }
}