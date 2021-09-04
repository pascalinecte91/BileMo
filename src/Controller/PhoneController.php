<?php

namespace App\Controller;


use App\Entity\Phone;
use OpenApi\Annotations as OA;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use JMS\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Cache\FilesystemCache;

/**
 * @Route("/api/phone")
 * @OA\Tag(name="Phones")
 */
class PhoneController extends AbstractController
{
    protected $serializer;
    protected $phoneRepository;



    public function __construct(
        SerializerSerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $manager

    ) {
        $this->serializer = $serializer;
        $this->validatorInterface = $validator;
        $this->entityManagerInterface = $manager;
    }

    /**
     *@Route("",  name="phones_list", methods={"GET"})
     *   @OA\Response(
     *      response="200",
     *      description="Liste des telephones",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              ref=@Model(type=Phone::class, groups={"list"})
     *          )
     *     )
     * ) 
     *   @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType = "application/json",
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
     *      )
     * )
     * @Cache(expires="tomorrow")
     */

    public function index(Request $request, PhoneRepository $phoneRepository)
    {


        $page = $request->query->get('page');
        if (($page === null) || $page < 1) {
            $page = 1;
        }
        $cache = new FilesystemAdapter();
        $phones = $cache->get('phones_index_' . $page, function (ItemInterface $item) use ($phoneRepository, $page) {
            $item->expiresAfter(86400);
            $phones = $phoneRepository->findAllPhones($page, 6);
            return iterator_to_array($phones);
        });




        $phonesJson = $this->serializer->serialize($phones, "json", SerializationContext::create()->setGroups(['list']));

        return  JsonResponse::fromJsonString($phonesJson);
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
     * @Cache(expires="tomorrow")
     */

    public function show(PhoneRepository $phoneRepository, $id)
    {
        $cache = new FilesystemAdapter();
        $phone = $cache->get('phone_show_' . $id, function (ItemInterface $item) use ($phoneRepository, $id) {
            $item->expiresAfter(86400);
            $phone = $phoneRepository->find($id);
            return $phone;
        });

        if (!$phone) {
            throw new NotFoundHttpException();
        }

        $phoneJson = $this->serializer->serialize($phone, "json", SerializationContext::create()->setGroups(['show']));


        return  JsonResponse::fromJsonString($phoneJson);
    }
}
