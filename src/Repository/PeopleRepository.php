<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\People;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PeopleRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, People::class);
        $this->manager = $manager;
    }

    public function createPeople(string $firstname, string $lastname, string $date, string $nationality): void
    {
        $people = new People();

        $people
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setDateOfBirthday(new DateTime($date))
            ->setNationality($nationality);

        $this->manager->persist($people);
        $this->manager->flush();
    }

    public function updatePeople(
        People $people,
        ?string $firstname,
        ?string $lastname,
        ?string $dateOfBirth,
        ?string $nationality
    ): void {
        if ($people) {
            $people->setFirstname($firstname);
        }

        if ($people) {
            $people->setLastname($lastname);
        }

        if ($dateOfBirth) {
            $dateOfBirthFormat = new DateTime($dateOfBirth);

            $people->setDateOfBirthday($dateOfBirthFormat);
        }

        if ($nationality) {
            $people->setNationality($nationality);
        }

        $this->manager->persist($people);
        $this->manager->flush();
    }

    public function removePeople(People $people): void
    {
        $this->manager->remove($people);
        $this->manager->flush();
    }
}