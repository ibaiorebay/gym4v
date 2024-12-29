<?php

namespace App\Service;

use app\Model\MonitorDTO as ModelMonitorDTO;
use App\Models\MonitorDTO;
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
        return $this->entityManager->getRepository(ModelMonitorDTO::class)->findAll();
    }

    public function getMonitorById(int $id): ?ModelMonitorDTO
    {
        return $this->entityManager->getRepository(ModelMonitorDTO::class)->find($id);
    }

    public function createMonitor(ModelMonitorDTO $monitor): ModelMonitorDTO
    {
        $this->entityManager->persist($monitor);
        $this->entityManager->flush();
        return $monitor;
    }

    public function updateMonitor(ModelMonitorDTO $monitor): ModelMonitorDTO
    {
        $this->entityManager->flush();
        return $monitor;
    }

    public function deleteMonitor(ModelMonitorDTO $monitor): void
    {
        $this->entityManager->remove($monitor);
        $this->entityManager->flush();
    }
}
