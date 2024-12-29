<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MonitorController extends AbstractController
{
    #[Route('/monitor', name: 'app_monitor')]
    public function index(): Response
    {
        return $this->render('monitor/index.html.twig', [
            'controller_name' => 'MonitorController',
        ]);
    }
}
