<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use OpenApi\Annotations as OA;
use Doctrine\ORM\EntityManager;
use Swagger\Annotations as SWG;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



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
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ) {
        $this->SerializerInterface = $serializer;
        $this->ValidatorInterface = $validator;
        $this->EntityManagerInterface = $manager;
    }


/**
 * @Route("", name="customer_list", methods={"GET"})
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
        
        $json = $serializer->serialize($customers, 'json', [
            'groups' => 'list'
        ]);
        $response = new Response($json, 200, [
            "content-type" => "application/json"
        ]);
        return $response;
    }


    /**
     * @Route("/{id}", name="customer", methods={"GET"})
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
    public function show(Customer $customer, SerializerInterface $serializer)

    {
        return $this->json($customer, Response::HTTP_OK, [], [
            'groups' => ['show', 'list']
        ]);
        dd($customer);
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
    public function newCustomer(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $customer = new Customer();
       
    
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

        $user = $userRepository->findOneByName('toto');
        $customer->setUser($user);
     
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $this->json($customer, Response::HTTP_OK, [], [
            'groups' => ['show', 'list']
        ]);
    }
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
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();

            return new Response(null, 204);
        }
        throw new HttpException(404, "you can't delete a customer who does not exist !");
    }
}