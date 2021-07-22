<?php
declare(strict_types=1);

namespace App\DTO;

use App\Entity\People;
use DateTime;

class PeopleDTO
{
    public int $id;

    public string $firstname;

    public string $lastname;

    public DateTime $dateOfBirth;

    public string $nationality;

    public function __construct(People $people)
    {
        $this->id = $people->getId();
        $this->firstname = $people->getFirstname();
        $this->lastname = $people->getLastname();
        $this->dateOfBirth = $people->getDateOfBirthday();
        $this->nationality = $people->getNationality();
    }
}