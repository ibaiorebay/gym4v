<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ActivityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\ActivityDTO;
use App\Models\ActivityTypeDTO;
use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\Monitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActivityController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        private LoggerInterface $logger,
        private ActivityService $activityService,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    #[Route('/activities', methods: ['GET'])]
    public function getAllActivities(Request $request): JsonResponse
    {
        $date = $request->query->get('date');

        try {
            $activities = $date
                ? $this->activityService->getActivitiesByDate(new \DateTime($date))
                : $this->activityService->getAllActivities();

            // Mapear entidades a DTO
            $activityDTOs = array_map([$this, 'mapEntityToDTO'], $activities);

            return $this->json($activityDTOs);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/activities/{id}', methods: ['GET'])]
    public function getAllActivitiesById(int $id): JsonResponse
    {
        try {
            $activity = $this->activityService->getActivityById($id);

            if (!$activity) {
                return $this->json(['error' => 'Activity not found'], 404);
            }

            $activityDTO = $this->mapEntityToDTO($activity);

            return $this->json($activityDTO);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/activities', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            // Validar y crear DTO
            $activityType = $this->entityManager->getRepository(ActivityType::class)
                ->find($data['activityTypeId'] ?? null);

            if (!$activityType) {
                return $this->json(['error' => 'Activity type not found.'], 404);
            }

            $monitors = $this->entityManager->getRepository(Monitor::class)
                ->findBy(['id' => $data['monitorIds'] ?? []]);

            if (count($monitors) < $activityType->getNumberMonitors()) {
                return $this->json(['error' => 'Not enough monitors assigned for this activity type.'], 400);
            }

            $dateStart = \DateTime::createFromFormat('Y-m-d\TH:i:s', $data['dateStart']);
            if (!$dateStart) {
                return $this->json(['error' => 'Invalid date format. Use format: YYYY-MM-DDTHH:MM:SS.'], 400);
            }

            $dateEnd = (clone $dateStart)->modify('+90 minutes');

            // Crear entidad y persistirla
            $activity = new Activity();
            $activity->setActivityType($activityType);
            $activity->setDateStart($dateStart);
            $activity->setDateEnd($dateEnd);

            foreach ($monitors as $monitor) {
                $activity->createMonitor($monitor);
            }

            $this->activityService->createActivity($activity);

            return $this->json($this->mapEntityToDTO($activity), 201);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/activities/{id}', methods: ['PUT'])]
    public function updateActivity(int $id, Request $request): JsonResponse
    {
        $activity = $this->activityService->getActivityById($id);
        if (!$activity) {
            return $this->json(['error' => 'Activity not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $activityType = $this->entityManager->getRepository(ActivityType::class)
                ->find($data['activityTypeId'] ?? null);

            if (!$activityType) {
                return $this->json(['error' => 'Activity type not found.'], 404);
            }

            $monitors = $this->entityManager->getRepository(Monitor::class)
                ->findBy(['id' => $data['monitorIds'] ?? []]);

            if (count($monitors) < $activityType->getNumberMonitors()) {
                return $this->json(['error' => 'Not enough monitors assigned for this activity type.'], 400);
            }

            $dateStart = \DateTime::createFromFormat('Y-m-d\TH:i:s', $data['dateStart']);
            if (!$dateStart) {
                return $this->json(['error' => 'Invalid date format. Use format: YYYY-MM-DDTHH:MM:SS.'], 400);
            }

            $dateEnd = (clone $dateStart)->modify('+90 minutes');

            $activity->setActivityType($activityType);
            $activity->setDateStart($dateStart);
            $activity->setDateEnd($dateEnd);

            foreach ($monitors as $monitor) {
                $activity->createMonitor($monitor);
            }

            $this->activityService->updateActivity($activity);

            return $this->json($this->mapEntityToDTO($activity));
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/activities/{id}', methods: ['DELETE'])]
    public function deleteActivity(int $id): JsonResponse
    {
        $activity = $this->activityService->getActivityById($id);
        if (!$activity) {
            return $this->json(['error' => 'Activity not found'], 404);
        }

        $this->activityService->deleteActivity($activity);

        return $this->json(['message' => 'Activity deleted successfully'], 204);
    }

    private function mapEntityToDTO(Activity $activity): ActivityDTO
    {
        $activityTypeDTO = new ActivityTypeDTO(
            $activity->getActivityType()->getId(),
            $activity->getActivityType()->getName(),
            $activity->getActivityType()->getNumberMonitors()
        );

        $monitorIds = array_map(
            fn($activityMonitor) => $activityMonitor->getMonitor()->getId(),
            $activity->getActivityid()->toArray()
        );

        return new ActivityDTO(
            $activity->getId(),
            $activity->getActivityType()->getName(),
            $activity->getDateStart(),
            $activity->getDateEnd(),
            $activityTypeDTO,
            $monitorIds
        );
    }
}
