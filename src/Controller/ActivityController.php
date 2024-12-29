<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ActivityService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivityController extends AbstractController
{
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


}
