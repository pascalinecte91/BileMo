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
     *     response=200,
     *     description="LOGIN",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Response(
     *      response="403",
     *      description="FORBIDDEN acces non autorisé",
     * )
     *      @OA\Response(
     *      response="404",
     *      description="URI est peut-être incorrect ou la ressource a peut-être été supprimée.",
     * )
     */
    public function loginAction(): Response
    {
        throw new BadRequestHttpException();
    }
}
