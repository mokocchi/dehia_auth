<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Psr\Log\LoggerInterface;

/**
 * @Route("/v1.0/me")
 */
class ValidateJWTController extends AbstractFOSRestController
{
    private $logger;
    
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
    /**
     * @Rest\Get(name="get_user_jwt")
     */
    public function validateJWT()
    {
        $usuario = $this->getUser();
        return $this->handleView($this->view($usuario));
    }
}
