<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use app\Service\MonitorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\JsonResponse;
use app\Model\MonitorDTO;

class MonitorController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private MonitorService $monitorService)
    {}

    #[Route('/monitor', name: 'app_monitor')]
    public function GetAllMonitors(): Response
    {
        return $this->json($this->monitorService->getAllMonitors());
    }

    #[Route('/monitor', name: 'post_monitor', methods:['POST'], format: 'json')]
    public function newMonitors(#[MapRequestPayload(
        acceptFormat: 'json',
        validationFailedStatusCode: Response::HTTP_NOT_FOUND
    )] MonitorDTO $monitorDto): JsonResponse
    {
        // Inserto el objeto
        $this->monitorService->createMonitor($monitorDto);

        //Contesto
        return $this->json($monitorDto);
        
    }
}
