<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\MovieHasType;

class MovieHasTypeDTO
{
    public int $movieId;

    public string $type;

    public function __construct(MovieHasType $movieHasType)
    {
        $this->movieId = $movieHasType->getMovie()->getId();
        $this->type = $movieHasType->getType()->getName();
    }
}