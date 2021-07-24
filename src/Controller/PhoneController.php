<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


/**
 * @Route("/api/phone")
 * @OA\Tag(name="Phones")
 *    @OA\Response(
 *      response="403",
 *      description="FORBIDDEN acces non autorisé",
 * )
 *      @OA\Response(
 *      response="404",
 *      description="URI est peut-être incorrect ou la ressource a peut-être été supprimée.",
 * )
 * 
 */


class PhoneController extends AbstractController
{
    /**
     *@Route("",  name="phone_index", methods={"GET"})
     *   @OA\Response(
     *      response="200",
     *      description="Liste",
     *   @OA\Schema(
     *      type="array",
     *   @OA\Items(
     *      ref=@Model(type=Phone::class))
     *     )
     * )
     */

    public function index(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {

        $page = $request->query->get('page');
        if (is_null($page) || $page < 1) {
            $page = 1;
        }
            $phones = $phoneRepository->findAllPhones($page, 5);
        
            $json = $serializer->serialize($phones, 'json', ['groups' => 'list']);
            $response = new Response($json, 200, [
                "content-type" => "application/json"
            ]);
            return $response;
            
        
    }

    /**
     * @Route("/show/{id}", name="show_phone", methods={"GET"})
     * @ParamConverter("phone", class="App:Phone")
     *     @OA\Parameter(name="page", in="query", 
     *     @OA\Schema(type="integer"))
     *     @Cache(expires="+5 minutes")
     *     @OA\Response(
     *         response="200",
     *         description="Returns Phone"
     *) 
     */
    public function show(Phone $phone): Response
    {
        return $this->json($phone, Response::HTTP_OK, [
            'groups' => ['show']
        ]);
    }
}
