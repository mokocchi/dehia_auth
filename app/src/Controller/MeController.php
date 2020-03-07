<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * @Route("/validate")
 */
class MeController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(name="validate")
     * 
     */
    public function me()
    {
        $usuario = $this->getUser();
        return $this->handleView($this->view($usuario));
    }
}
