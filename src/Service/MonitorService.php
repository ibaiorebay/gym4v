<?php

namespace App\Service;

use App\Models\MonitorDTO;
use App\Entity\Monitor;  // Importa la entidad Monitor
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
        // Usamos la entidad Monitor para interactuar con la base de datos
        return $this->entityManager->getRepository(Monitor::class)->findAll();
    }

    public function getMonitorById(int $id): ?Monitor
    {
        return $this->entityManager->getRepository(Monitor::class)->find($id);
    }

    public function createMonitor(MonitorDTO $monitorDTO): Monitor
    {
        // Aquí transformamos el DTO a la entidad Monitor antes de persistirla
        $monitor = new Monitor();
        $monitor->setName($monitorDTO->getName());
        // Mapear otros datos del DTO a la entidad Monitor...

        $this->entityManager->persist($monitor);
        $this->entityManager->flush();
        return $monitor;
    }

    public function updateMonitor(Monitor $monitor): Monitor
    {
        $monitor = $this->entityManager->getRepository(Monitor::class)->find($monitor->getId());
        if ($monitor) {
            $monitor->setName($monitor->getName());
            // Mapear otros campos del DTO a la entidad Monitor...

            $this->entityManager->flush();
        }
        return $monitor;
    }

    public function deleteMonitor(Monitor $monitor): void
    {
        $monitor = $this->entityManager->getRepository(Monitor::class)->find($monitor->getId());
        if ($monitor) {
            $this->entityManager->remove($monitor);
            $this->entityManager->flush();
        }
    }
}

