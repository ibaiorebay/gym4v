<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\ActivityType;
use Doctrine\ORM\EntityManagerInterface;

class ActivityService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllActivities(): array
    {
        return $this->entityManager->getRepository(Activity::class)->findAll();
    }

    public function getActivityById(int $id): ?Activity
    {
        return $this->entityManager->getRepository(Activity::class)->find($id);
    }

    public function getActivitiesByDate(\DateTime $date): array
    {
        $startOfDay = $date->setTime(0, 0, 0);
        $endOfDay = $date->setTime(23, 59, 59);

        return $this->entityManager->getRepository(Activity::class)->createQueryBuilder('a')
            ->where('a.dateStart >= :startOfDay AND a.dateStart <= :endOfDay')
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getResult();
    }

    public function createActivity(Activity $activity): Activity
    {
        $this->entityManager->persist($activity);
        $this->entityManager->flush();
        return $activity;
    }

    public function updateActivity(Activity $activity): Activity
    {
        $this->entityManager->flush();
        return $activity;
    }

    public function deleteActivity(Activity $activity): void
    {
        $this->entityManager->remove($activity);
        $this->entityManager->flush();
    }
}
