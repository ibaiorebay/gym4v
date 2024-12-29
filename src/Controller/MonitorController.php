<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use app\Service\MonitorService;
use Psr\Log\LoggerInterface;

class MonitorController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private MonitorService $monitorService)
    {}
    #[Route('/monitor', name: 'app_monitor')]
    public function GetAllMonitors(): Response
    {
        return $this->json($this->monitorService->getAllMonitors());
    }
}
