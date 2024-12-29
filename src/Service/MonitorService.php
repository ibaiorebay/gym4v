<?php

namespace App\Service;

use App\Entity\Monitor;
use Doctrine\ORM\EntityManagerInterface;

class MonitorService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllMonitors(): array
    {
        return $this->entityManager->getRepository(Monitor::class)->findAll();
    }

    public function getMonitorById(int $id): ?Monitor
    {
        return $this->entityManager->getRepository(Monitor::class)->find($id);
    }

    public function createMonitor(Monitor $monitor): Monitor
    {
        $this->entityManager->persist($monitor);
        $this->entityManager->flush();
        return $monitor;
    }

    public function updateMonitor(Monitor $monitor): Monitor
    {
        $this->entityManager->flush();
        return $monitor;
    }

    public function deleteMonitor(Monitor $monitor): void
    {
        $this->entityManager->remove($monitor);
        $this->entityManager->flush();
    }
}
