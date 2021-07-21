<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="movie_has_type")
 * @ORM\Entity(repositoryClass="App\Repository\MovieHasTypeRepository")
 */
class MovieHasType
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie")
     * @ORM\JoinColumn(name="movie_id", referencedColumnName="id", nullable=false)
     */
    protected Movie $movie;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    protected Type $type;

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): Type
    {
        return $this->type;
    }
}