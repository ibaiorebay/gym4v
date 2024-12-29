<?php

namespace App\Service;

use App\Entity\ActivityType;
use Doctrine\ORM\EntityManagerInterface;

class ActivityTypeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllActivityTypes(): array
    {
        return $this->entityManager->getRepository(ActivityType::class)->findAll();
    }

    public function getActivityTypeById(int $id): ?ActivityType
    {
        return $this->entityManager->getRepository(ActivityType::class)->find($id);
    }
}
