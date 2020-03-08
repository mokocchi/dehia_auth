<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

/**
 * @Route("/validate")
 */
class ValidateController extends AbstractFOSRestController
{
    /**
     * Valida un access_token y retorna el usuario que representa
     * @Rest\Get(name="validate")
     * 
     * @SWG\Response(
     *     response="200",
     *     description="Operación exitosa",
     *     @SWG\Schema(
     *        type="object",
     *        @SWG\Property(property="role", type="string", description="Rol del usuario", example="ROLE_AUTOR"),
     *        @SWG\Property(property="nombre", type="string", description="Nombre del usuario"),
     *        @SWG\Property(property="apellido", type="string", description="Apellido del usuario"),
     *        @SWG\Property(property="email", type="string", description="Email del usuario", example="user@example.com"),
     *        @SWG\Property(property="googleid", type="integer", description="ID de google del usuario")
     *    )
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="Error interno del servidor"
     * )
     * 
     * @SWG\Parameter(
     *     required=true,
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer token",
     * )
     *
     * @SWG\Tag(name="Auth")
     */
    public function validate()
    {
        $usuario = $this->getUser();
        return $this->handleView($this->view($usuario));
    }
}
