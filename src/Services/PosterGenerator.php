<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\Console\Command\Command;

class PosterGenerator
{
    private const LIMIT = 10;

    private EntityManagerInterface $em;

    private ImdbService $imdbService;

    public function __construct(EntityManagerInterface $em, ImdbService $imdbService)
    {
        $this->em = $em;
        $this->imdbService = $imdbService;
    }

    public function generate(): int
    {
        $movies = $this->getMoviesForImdbInfo();

        /**
         * @var Movie $movie
         */
        foreach ($movies as $movie) {
            $url = $this->imdbService->findPosterURL($movie->getTitle());

            $movie->setPosterUrl($url);
        }

        return Command::SUCCESS;
    }

    private function getMoviesForImdbInfo(): Generator
    {
        $excludedMovies = [];

        if (!gc_enabled()) {
            gc_enable();
        }

        do {
            $movies = $this
                ->em
                ->getRepository(Movie::class)
                ->getMoviesForImdbInfo(self::LIMIT, $excludedMovies);

            foreach ($movies as $movie) {
                $excludedMovies[] = $movie;
                yield $movie;
            }

            $this->em->flush();
            $this->em->clear();

            gc_collect_cycles();
        } while (!empty($movies));
    }
}