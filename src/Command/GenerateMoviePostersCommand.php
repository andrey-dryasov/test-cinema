<?php

namespace App\Command;

use App\Services\PosterGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMoviePostersCommand extends Command
{
    protected static $defaultName = 'app:generate-movie-posters';

    private PosterGenerator $generator;

    public function __construct(PosterGenerator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Synchronize poster data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->generator->generate();

        return Command::SUCCESS;
    }
}