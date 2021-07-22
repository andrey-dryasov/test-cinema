<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="people")
 * @ORM\Entity(repositoryClass="App\Repository\PeopleRepository")
 */
class People
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    protected string $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    protected string $lastname;

    /**
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=false)
     */
    protected DateTime $dateOfBirthday;

    /**
     * @ORM\Column(name="nationality", type="string", nullable=false)
     */
    protected string $nationality;

    public function getId(): int
    {
        return $this->id;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setDateOfBirthday(DateTime $dateOfBirthday): self
    {
        $this->dateOfBirthday = $dateOfBirthday;

        return $this;
    }

    public function getDateOfBirthday(): DateTime
    {
        return $this->dateOfBirthday;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }
}