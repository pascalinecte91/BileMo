<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\SerializerInterface;



/**
 * @Route("/api/phone")
 * @OA\Tag(name="Phones")
 *     @OA\Response(
 *     response = 401,
 *     description = "information incorrects ou token expiré"
 * )
 *     @OA\Response(
 *     response="403",
 *     description="FORBIDDEN acces non autorisé",
 * )
 */
class PhoneController extends AbstractController
{
    /**
     *@Route("",  name="phones_list", methods={"GET"})
     *   @OA\Response(
     *      response=JsonResponse::HTTP_OK,
     *      description="Liste des telephones",
     *   @OA\Schema(
     *      type="array",
     * )
     * ) 
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType = "application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property = "id",
     *                  description = "identifiant",
     *                  type = "integer"
     *               ),
     *              @OA\Property(
     *                  property = "name",
     *                  description = "Nom du telephone",
     *                  type = "string"
     *              ),
     *              @OA\Property(
     *                  property = "Couleur",
     *                  description = "couleur du téléphone",
     *                  type = "string"
     *              ),
     *              @OA\Property(
     *                  property = "memoire",
     *                  description = "capacite memoire",
     *                  type = "string"
     *              ),
     *              @OA\Property(
     *                  property = "description",
     *                  description = "description complète en detail",
     *                  type = "string"
     *              ),
     *          )
     *      )
     * )
     */

    public function index(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        if ($page === null) {
            $response = "400  mauvaise requete";
        }
        $phones = $phoneRepository->findAllPhones($page, 5);

        $json = $serializer->serialize($phones, 'json', [
            'groups' => 'list'
        ]);
            $response = new Response($json, 200, [
            "content-type" => "application/json"
            ]);
        return $response;
    }

    /**
     * @Route("/{id}", name="phone_detail", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Returns Phone",
     *     @OA\JsonContent(
     *          ref=@Model(type=Phone::class, groups={"show"})
     *     )
     * ) 
     * @OA\Response(
     *     response="404",
     *     description="URI est peut-être incorrect ou la ressource a peut-être été supprimée.",
     *          )
     *      )
     *     )
     * )
     * @param Phone|null $phone
     *
     * @return Phone
     */

    public function show(Phone $phone): Response
    {
        return $this->json($phone, Response::HTTP_OK, [
            'groups' => ['show']
        ]);
    }
}
