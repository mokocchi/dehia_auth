<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/health-check")
 */
class HealthController extends AbstractFOSRestController
{
    /**
     * Valida un access_token y retorna el usuario que representa
     * @Rest\Get(name="health-check")
     * 
     */
    public function healthCheck()
    {
        return new JsonResponse(["status" => "ok"]);
    }
}
