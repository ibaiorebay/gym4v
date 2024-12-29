<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use app\Service\ActivityTypeService;
use Psr\Log\LoggerInterface;

class ActivityTypeController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private ActivityTypeService $activityTypeService)
    {}

    #[Route('/activity-type', name: 'app_activity_type')]
    public function getAllTypes(): Response
    {
        return $this->json($this->activityTypeService->getAllActivityTypes());
    }
}