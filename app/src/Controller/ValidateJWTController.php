<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * @Route("/v1.0/users/me")
 */
class ValidateJWTController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(name="get_user_jwt")
     */
    public function validateJWT()
    {
        $usuario = $this->getUser();
        return $this->handleView($this->view($usuario));
    }
}
