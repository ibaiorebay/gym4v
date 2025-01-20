<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ActivityTypeService;    
use Psr\Log\LoggerInterface;
use app\Models\ActivityTypeDTO; // Importamos el DTO

class ActivityTypeController extends AbstractController
{
    public function __construct(private LoggerInterface $logger, private ActivityTypeService $activityTypeService)
    {}

    #[Route('/activity-types', name: 'app_activity_type')]
    public function GetAllTypes(): Response
    {
        // Obtenemos todos los tipos de actividad desde el servicio
        $activityTypes = $this->activityTypeService->getAllActivityTypes();

        // Creamos un array donde almacenaremos los DTOs transformados
        $activityTypesDTO = [];

        // Convertimos cada entidad ActivityType en un DTO
        foreach ($activityTypes as $activityType) {
            // Creamos el DTO con la información de la entidad
            $activityTypeDTO = new ActivityTypeDTO(
                $activityType->getId(),                 // ID del tipo de actividad
                $activityType->getName(),               // Nombre del tipo de actividad
                $activityType->getNumberMonitors()      // Número de monitores necesarios
            );
            
            // Agregamos el DTO al array
            $activityTypesDTO[] = $activityTypeDTO;
        }

        // Devolvemos la respuesta con los DTOs en formato JSON
        return $this->json($activityTypesDTO);
    }
}
