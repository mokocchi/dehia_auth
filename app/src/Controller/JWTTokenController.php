<?php

namespace App\Controller;

use App\Api\ApiProblem;
use App\Api\ApiProblemException;
use App\Entity\Role;
use App\Entity\Usuario;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Google_Client;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/v1.0/tokens")
 */
class JWTTokenController extends AbstractFOSRestController
{
    private $encoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->encoder = $jwtEncoder;
    }

    private function register($client, $userid, $id_token)
    {
        $httpClient = $client->authorize();

        // make an HTTP request
        $response = $httpClient->get('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $id_token);
        $data = json_decode((string) $response->getBody());

        $em = $this->getDoctrine()->getManager();

        $usuarioDb = $em->getRepository(Usuario::class)->findOneBy(["googleid" => $userid]);
        if (!is_null($usuarioDb)) {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "Ya existe un usuario con este email", "Ya existe un usuario con este email")
            );
        }

        $user = new Usuario();
        $user->setEmail($data->email);
        $user->setNombre($data->given_name);
        $user->setApellido($data->family_name);
        $user->setGoogleid($userid);
        $role = $em->getRepository(Role::class)->findOneBy(["name" => "ROLE_AUTOR"]);
        $user->addRole($role);

        $em->persist($user);
        $em->flush();
        return $user;
    }

    /**
     * @Rest\Post(name="get_JWT_token")
     */
    public function getJWTTokenAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (is_null($data)) {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "JSON inválido", "Hubo un problema con el request")
            );
        }

        if (!array_key_exists('token', $data)) {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "No se encontró el id_token de usuario", "Ocurrió un error en la autenticación")
            );
        }

        $id_token = $data['token'];

        if (is_null($id_token)) {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "No se encontró el id_token de usuario", "Ocurrió un error en la autenticación")
            );
        }

        if (!(preg_match('/^[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.?[A-Za-z0-9-_]*$/', $id_token))) {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "El id_token no es un JWT válido", "Ocurrió un error en la autenticación")
            );
        }

        $client = new Google_Client(['client_id' => $_ENV["GOOGLE_CLIENT_ID"]]);
        $payload = $client->verifyIdToken($id_token);
        $usuario = null;
        if ($payload) {
            if ($payload['aud'] == $_ENV["GOOGLE_CLIENT_ID"]) {
                $userid = $payload['sub'];
                $usuario = $this->getDoctrine()->getManager()->getRepository(Usuario::class)->findOneBy(['googleid' => $userid]);
                if (is_null($usuario)) {
                    $usuario = $this->register($client, $userid, $id_token);
                }

                $token = $this->encoder
                    ->encode([
                        'gid' => $usuario->getGoogleid(),
                        'exp' => time() + 3600
                    ]);

                return $this->handleView($this->view(["access_token" => $token, "expires_in" => 3600]));
            } else {
                throw new ApiProblemException(
                    new ApiProblem(Response::HTTP_BAD_REQUEST, "El id_token no fue emitido para esta aplicación", "Ocurrió un error en la autenticación")
                );
            }
        } else {
            throw new ApiProblemException(
                new ApiProblem(Response::HTTP_BAD_REQUEST, "El id_token no es válido", "Ocurrió un error con la autenticación")
            );
        }
    }
}
