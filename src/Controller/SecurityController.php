<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;


class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="security_login", methods={"POST"})
     * @OA\Tag(name="Connexion")
     * @OA\Response(
     *     response=JsonResponse::HTTP_OK,
     *     description="Renvoi la liste des utilisateurs"
     * )
     * @OA\Response(
     *     response = 401,
     *     description = "Informations Invalides"
     * )
     * @OA\Response(
     *     response="403",
     *     description="FORBIDDEN acces non autorisé",
     * )
     * @OA\Response(
     *     response="404",
     *     description="URI est peut-être incorrect ou la ressource a peut-être été supprimée.",
     * )
     * @OA\RequestBody(
     *     description = "User credentials",
     *     @OA\MediaType(
     *         mediaType = "application/json",
     *     @OA\Schema(
     *          @OA\Property(
     *              property = "email",
     *              description = "Adresse Mail de l'utilisateur",
     *              type = "string"
     *           ),
     *          @OA\Property(
     *              property = "password",
     *              description = "Mot de passe de l'utilisateur",
     *              type = "string",
     *              format = "password"
     *           ),
     *          @OA\Property(
     *              property = "username",
     *              description = "Nom ",
     *              type = "string",
     *              format = "string"        
     *          )
     *     )
     * )
     * )
     * @Security()
     */
    public function loginAction(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Requête non valide".'
            ], 400);
        }
        return $this->json(
            [
                'user' => $this->getUser() ? $this->getUser()->getId() : null
            ]
        );
    }
}
