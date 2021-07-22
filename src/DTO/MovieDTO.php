<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\Movie;

class MovieDTO
{
    public int $id;

    public string $title;

    public int $duration;

    public function __construct(Movie $movie)
    {
        $this->id = $movie->getId();
        $this->title = $movie->getTitle();
        $this->duration = $movie->getDuration();
    }
}