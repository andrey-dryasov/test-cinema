<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\MovieHasPeople;

class MovieHasPeopleDTO
{
    public int $movieId;

    public int $peopleId;

    public string $role;

    public ?string $significance;

    public function __construct(MovieHasPeople $movieHasPeople)
    {
        $this->movieId = $movieHasPeople->getMovie()->getId();
        $this->peopleId = $movieHasPeople->getPeople()->getId();
        $this->role = $movieHasPeople->getRole();
        $this->significance = $movieHasPeople->getSignificance();
    }
}