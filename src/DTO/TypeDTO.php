<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\Type;

class TypeDTO
{
    public int $id;

    public string $name;

    public function __construct(Type $type)
    {
        $this->id = $type->getId();
        $this->name = $type->getName();
    }
}