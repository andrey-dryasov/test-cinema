<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected string $title;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected int $duration;

    /**
     * @ORM\Column(name="poster_url", type="string", length=512, nullable=true)
     */
    protected ?string $posterUrl = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setPosterUrl(?string $posterUrl): self
    {
        $this->posterUrl = $posterUrl;

        return $this;
    }

    public function getPosterUrl(): ?string
    {
        return $this->posterUrl;
    }
}