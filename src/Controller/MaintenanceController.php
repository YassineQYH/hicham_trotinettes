<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{
    #[Route('/maintenance', name: 'app_maintenance')]
    public function index(): Response
    {
        // Utiliser renderView + Response pour s'assurer que rien ne s'ajoute après
        $content = $this->renderView('maintenance/index.html.twig');

        $response = new Response($content, Response::HTTP_SERVICE_UNAVAILABLE);

        // Désactive la web debug toolbar pour cette réponse
        $response->headers->set('X-Debug-Toolbar', '0');

        return $response;
    }
}
