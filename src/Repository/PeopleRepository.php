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

    public function createPeople(string $firstname, string $lastname, DateTime $date, string $nationality): void
    {
        $people = new People();

        $people
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setDateOfBirthday($date)
            ->setNationality($nationality);

        $this->manager->persist($people);
        $this->manager->flush();
    }
}