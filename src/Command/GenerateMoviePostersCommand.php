<?php

namespace App\Command;

use App\Entity\Movie;
use App\Services\ImdbService;
use Doctrine\ORM\EntityManagerInterface;
use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImdbSynchronizeCommand extends Command
{
    protected static $defaultName = 'app:generate-movie-posters';

    private const LIMIT = 10;
    private const OFFSET = 0;

    private EntityManagerInterface $em;

    private ImdbService $imdbService;

    public function __construct(EntityManagerInterface $em, ImdbService $imdbService)
    {
        $this->em = $em;
        $this->imdbService = $imdbService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize poster data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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