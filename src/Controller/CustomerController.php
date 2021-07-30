<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Hateoas\Configuration\Annotation as Hateoas;
use Swagger\Annotations as SWG;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;
use Symfony\Component\Serializer\SerializerInterface;



/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
/**
 * @Route("/api/customer")
 * @OA\Tag(name="customers ")
 
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
     * )
     * )
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
     * @Route("/show/{id}", name="customer", methods={"GET"})
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
    public function show(Customer $customer): Response

    {
        return $this->json($customer, Response::HTTP_OK, [
            'groups' => ['show']
        ]);
    }

    /**
     * @Route("/new", name="customer_new", methods={"POST"})
     *      @OA\Response(
     *          response="200",
     *          description="creation ok",
     * )
     */
    public function newCustomer(Customer $customer)
    {
        $customer = new Customer();
        $customer->setName('toto');
        $customer->setEmail('toto@gmail.com');

        return $this->json($customer, Response::HTTP_OK, [
            'groups' => ['show', 'list']
        ]);
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     *      @OA\Response(
     *          response="200",
     *          description="suppression ok",
     * )
     */
    public function deleteCustomer(Customer $customer, EntityManagerInterface $entityManager)
    { {
            $entityManager->remove($customer);
            $entityManager->flush();
            return new Response(null, 204);
        }
        throw new HttpException(404, "you can't delete a customer who does not exist !");
    }
}
