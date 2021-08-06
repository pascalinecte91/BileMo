<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use OpenApi\Annotations as OA;
use Doctrine\ORM\EntityManager;
use Swagger\Annotations as SWG;
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
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Annotation\ParamConverter;
use JMS\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/customer")
 * @OA\Tag(name="Customers")
 *      @OA\Response(
 *      response = 401,
 *      description = "information incorrects ou token expiré"
 * )
 *      @OA\Response(
 *      response="403",
 *      description="FORBIDDEN acces non autorisé",
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
        $this->ValidatorInterface = $validator;
        $this->EntityManagerInterface = $manager;
    }


    /**
     * @Route("", name="customers_list", methods={"GET"})
     *     @OA\Response(
     *         response="200",
     *         description="Liste",
     *     @OA\Schema(
     *         type="array",
     *     )
     *  )
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *      mediaType = "application/json",
     *          @OA\Property(
     *          property = "id",
     *          description = "identifiant",
     *          type = "integer"
     *          ),
     *          @OA\Property(
     *          property = "email",
     *          description = "mail du customer",
     *          type = "string"
     *          ),
     *          @OA\Property(
     *          property = "nom",
     *          description = "nom du customer",
     *          type = "string"
     *          ),
     *          @OA\Property(
     *          property = "User",
     *          description = " rattaché au User",
     *          type = "string"
     *          ),
     *   )
     *)
     *)
     */
    public function index(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        if (is_null($page) || $page < 1) {
            $page = 1;
        }
        $customers = $customerRepository->findAllCustomers($page, 6);

        $customersJson = $this->serializer->serialize($customers, "json", SerializationContext::create()->setGroups(['list']));
        return  JsonResponse::fromJsonString($customersJson);
    }


    /**
     * @Route("/{id}", name="customer_detail", methods={"GET"})
     *      @OA\Response(
     *          response="200",
     *          description="detail ok",
     *      @OA\JsonContent(
     *          type="array",
     *      @OA\Items(
     *          ref=@Model(type=Customer::class))
     *  )
     * )
     */
    public function show(Customer $customer)

    {

        $customerJson = $this->serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['show']));
        return  JsonResponse::fromJsonString($customerJson);
        
    }

    /**
     * @Route("", name="customer_new", methods={"POST"})
     * @OA\RequestBody(
     *      required=true,
     *     @OA\MediaType(
     *         mediaType = "application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property = "id",
     *                  description = "identifiant",
     *                  type = "integer"
     *               ),
     *              @OA\Property(
     *                  property = "email",
     *                  description = "mail du customer",
     *                  type = "string"
     *              ),
     *              @OA\Property(
     *                  property = "nom",
     *                  description = "nom du customer",
     *                  type = "string"
     *              ),
     *          )
     *      )
     * )
     */
    public function newCustomer(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $customer->setUser($this->getUser());  
            $manager->persist($customer);
            $manager->flush();
           

            $customerJson = $this->serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['show']));
        return  JsonResponse::fromJsonString($customerJson);
        }
        throw new HttpException(404, "you can't creer a customer ");
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     *      @OA\Response(
     *          response="204",
     *          description="successful operation",
     *          @Security(name="Bearer")
     * )
     */
    public function deleteCustomer(Customer $customer, EntityManagerInterface $entityManager, Request $request)
    {

        if ($customer->getUser() === $this->getUser()) {
            $this->manager->remove($customer);dd($customer);
            $this->manager->flush();
            
            $customerJson = $this->serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['show']));
            return  JsonResponse::fromJsonString($customerJson);
        }
        throw new HttpException(404, "you can't delete a customer ");
    }
}