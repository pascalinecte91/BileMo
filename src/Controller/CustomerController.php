<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use JMS\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/api/customer")
 * @OA\Tag(name="Customers")
 *     @OA\Response(
 *     response = 401,
 *     description = "information incorrects ou token expiré"
 * )
 *     @OA\Response(
 *     response="403",
 *     description="FORBIDDEN acces non autorisé",
 * )
 *     @OA\Response(
 *     response="404",
 *     description="file not found",
 * )
 */
class CustomerController extends AbstractController
{
    protected $serializer;
    protected $customerRepository;

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
     * @Route("", name="customers_list", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Liste",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(
     *             ref=@Model(type=Customer::class, groups={"list"})
     *         )
     *     )
     * )
     * @OA\RequestBody(
     *     @OA\MediaType(
     *     mediaType = "application/json",
     *         @OA\Property(
     *         property = "id",
     *         description = "identifiant",
     *         type = "integer"
     *         ),
     *         @OA\Property(
     *         property = "email",
     *         description = "mail du customer",
     *         type = "string"
     *         ),
     *         @OA\Property(
     *         property = "nom",
     *         description = "nom du customer",
     *         type = "string"
     *         ),
     *         @OA\Property(
     *         property = "User",
     *         description = " rattaché au User",
     *         type = "string"
     *         ),
     *     )
     *     )
     *  )
     */
    public function index(Request $request, CustomerRepository $customerRepository)
    {
        $page = $request->query->get('page');
        if (is_null($page) || $page < 1) {
            $page = 1;
        }
        $cache = new FilesystemTagAwareAdapter();
        $customers = $cache->get('customers_index_' . $page, function (ItemInterface $item) use ($customerRepository, $page) {
            $item->expiresAfter(86400);
            $item->tag('customer_index');
            $customers = $customerRepository->findAllPaginatedByUserId($this->getUser(), $page, 6);
            return iterator_to_array($customers);
        });


        $context =  SerializationContext::create()->setGroups(array("list"));
        $customersJson = $this->serializer->serialize($customers, 'json', $context);

        return  JsonResponse::fromJsonString($customersJson);
    }

    /**
     * @Route("/{id}", name="customer_detail", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="detail ok",
     *     @OA\JsonContent(
     *         ref=@Model(type=Customer::class, groups={"show"})
     *     )
     * )
     */
    public function show(CustomerRepository $customerRepository, $id)
    {

        $cache = new FilesystemTagAwareAdapter();
        $cache->prune();
        $customer = $cache->get('customer_show_' . $id, function (ItemInterface $item) use ($customerRepository, $id) {
            $item->expiresAfter(86400);
            $customer = $customerRepository->findOneBy(['id' => $id, 'user' => $this->getUser()]);
            return $customer;
        });

        if (!$customer) {
            throw new NotFoundHttpException();
        }

        $customerJson = $this->serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['show']));
        return  JsonResponse::fromJsonString($customerJson);
    }

    /**
     * @Route("", name="customer_new", methods={"POST"})
     * @Security(name="Bearer")
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *         mediaType = "application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property = "email",
     *                 description = "mail du customer",
     *                 type = "string"
     *             ),
     *             @OA\Property(
     *                 property = "name",
     *                 description = "nom du customer",
     *                 type = "string"
     *             ),
     *         )
     *     )
     * )
     */
    public function newCustomer(Request $request)
    {
        $customer = new Customer();
        $customer->setUser($this->getUser());
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();


            $manager->persist($customer);

            $manager->flush();

            $cache = new FilesystemTagAwareAdapter();
            $cache->invalidateTags(['customer_index']);

            $customerJson = $this->serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['show']));
            return  JsonResponse::fromJsonString($customerJson, Response::HTTP_CREATED);
        }
        throw new HttpException(400, "vous avez une erreur dans votre requête ");
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     * @OA\Response(
     *     response="204",
     *     description="successful operation",
     *     @Security(name="Bearer")
     * )
     */
    public function deleteCustomer(Customer $customer)
    {
        $cache = new FilesystemTagAwareAdapter();
        $cache->delete('customer_show_' . $customer->getId());
        $cache->invalidateTags(['customer_index']);
        if ($this->getUser() !== $customer->getUser()) {
            throw new NotFoundHttpException();
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($customer);
        $manager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
