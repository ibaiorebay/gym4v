<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ActivityService;
use Psr\Log\LoggerInterface;

class ActivityController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private ActivityService $activityService) {}
    #[Route('/activity', name: 'app_activity')]
    public function GetAllActivities(): Response
    {
        return $this->json($this->activityService->getAllActivities());
    }
}
