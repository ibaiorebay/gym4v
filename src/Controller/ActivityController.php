<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ActivityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use app\Models\ActivityDTO;
use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\Monitor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ActivityController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(private LoggerInterface $logger, private ActivityService $activityService) {}
    #[Route('/activities', methods: ['GET'])]
    public function getAllActivities(Request $request): JsonResponse
    {
        // Obtener el parámetro de fecha del query string
        $date = $request->query->get('date');

        try {
            if ($date) {
                // Validar formato de la fecha
                $dateObject = \DateTime::createFromFormat('d-m-Y', $date);
                if (!$dateObject || $dateObject->format('d-m-Y') !== $date) {
                    return $this->json(['error' => 'Invalid date format. Use dd-MM-yyyy.'], 400);
                }
                // Obtener actividades por fecha
                $activities = $this->activityService->getActivitiesByDate($dateObject);
            } else {
                // Obtener todas las actividades
                $activities = $this->activityService->getAllActivities();
            }

            return $this->json($activities);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/activities', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            // Crear el DTO y validarlo
            $activityDTO = new ActivityDTO(
                $data['activityTypeId'] ?? null,
                $data['monitorIds'] ?? [],
                $data['dateStart'] ?? ''
            );

            $errors = $validator->validate($activityDTO);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = [
                        'field' => $error->getPropertyPath(),
                        'message' => $error->getMessage(),
                    ];
                }
                return $this->json(['errors' => $errorMessages], 400);
            }

            // Obtener el tipo de actividad
            $activityType = $this->entityManager->getRepository(ActivityType::class)->find($activityDTO->getActivityTypeId());
            if (!$activityType) {
                return $this->json(['error' => 'Activity type not found.'], 404);
            }

            // Validar los monitores
            $monitors = $this->entityManager->getRepository(Monitor::class)->findBy(['id' => $activityDTO->getMonitorIds()]);
            if (count($monitors) < $activityType->getNumberMonitors()) {
                return $this->json(['error' => 'Not enough monitors assigned for this activity type.'], 400);
            }

            // Validar y convertir la fecha de inicio
            $dateStart = \DateTime::createFromFormat('Y-m-d\TH:i:s', $activityDTO->getDateStart());
            if (!$dateStart) {
                return $this->json(['error' => 'Invalid date format. Use ISO 8601 format: YYYY-MM-DDTHH:MM:SS.'], 400);
            }

            $validStartTimes = ['09:00', '13:30', '17:30'];
            if (!in_array($dateStart->format('H:i'), $validStartTimes)) {
                return $this->json(['error' => 'Invalid start time. Allowed times are 09:00, 13:30, or 17:30.'], 400);
            }

            // Calcular la fecha de fin
            $dateEnd = clone $dateStart;
            $dateEnd->modify('+90 minutes');

            // Crear la actividad
            $activity = new Activity();
            $activity->setActivityType($activityType);
            $activity->setDateStart($dateStart);
            $activity->setDateEnd($dateEnd);

            foreach ($monitors as $monitor) {
                $activity->createMonitor($monitor);
            }

            // Guardar la actividad
            $this->activityService->createActivity($activity);

            return $this->json($activity, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
