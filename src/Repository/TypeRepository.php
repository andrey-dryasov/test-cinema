<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class TypeRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    ) {
        parent::__construct($registry, Type::class);
        $this->manager = $manager;
    }

    public function createType(string $name): void
    {
        $type = new Type();
        $type->setName($name);

        $this->manager->persist($type);
        $this->manager->flush();
    }

    public function updateType(Type $type, ?string $name): void
    {
        if ($name) {
            $type->setName($name);
        }

        $this->manager->persist($type);
        $this->manager->flush();
    }

    public function removeType(Type $type): void
    {
        $this->manager->remove($type);
        $this->manager->flush();
    }
}