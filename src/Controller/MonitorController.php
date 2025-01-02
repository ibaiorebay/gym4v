<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MonitorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Model\MonitorDTO;
use App\Entity\Monitor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MonitorController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private MonitorService $monitorService)
    {}

    #[Route('/monitor', name: 'app_monitor')]
    public function GetAllMonitor(): Response
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

    #[Route('/monitors/{id}', methods: ['PUT'])]
    public function updateMonitor(
        int $id,
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse {
        // Buscar el monitor existente
        $monitor = $this->monitorService->getMonitorById($id);
        if (!$monitor) {
            return $this->json(['error' => 'Monitor not found'], 404);
        }

        // Decodificar los datos de la solicitud
        $data = json_decode($request->getContent(), true);

        // Actualizar los campos del monitor
        $monitor->setName($data['name'] ?? $monitor->getName());
        $monitor->setEmail($data['email'] ?? $monitor->getEmail());
        $monitor->setPhone($data['phone'] ?? $monitor->getPhone());
        $monitor->setPhoto($data['photo'] ?? $monitor->getPhoto());

        // Validar los datos actualizados
        $errors = $validator->validate($monitor);
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

        // Guardar los cambios
        $this->monitorService->updateMonitor($monitor);

        return $this->json($monitor);
    }

    #[Route('/monitors/{id}', methods: ['DELETE'])]
    public function deleteMonitor(int $id): JsonResponse
    {
        // Buscar el monitor existente
        $monitor = $this->monitorService->getMonitorById($id);
        if (!$monitor) {
            return $this->json(['error' => 'Monitor not found'], 404);
        }

        // Eliminar el monitor
        $this->monitorService->deleteMonitor($monitor);

        return $this->json(['message' => 'Monitor deleted successfully'], 204);
    }
}
