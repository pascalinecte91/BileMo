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

use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/customer")
 * @OA\Tag(name="Users/customers ")
 *      @OA\Response(
 *      response="403",
 *      description="FORBIDDEN acces non autorisé",
 * )
 *      @OA\Response(
 *      response="404",
 *      description="URI est peut-être incorrect ou la ressource a peut-être été supprimée.",
 * )
 * 
 */
class CustomerController extends AbstractController
{
    protected $serializer;
    protected $customerRepository;

    public function __construct(SerializerInterface $serializer,CustomerRepository $customerRepository, ValidatorInterface $Validator)
    {
        $this->SerializerInterface = $serializer;
        $this->customerRepository = $customerRepository;
        $this->ValidatorInterface = $Validator;
    }   

    /**
    * @Route("", name="customer_list", methods={"GET"})
     *@OA\Parameter(name="page", in="query", @OA\Schema(type="integer"))
     *@OA\Response(
     *     response="200",
     *     description="Liste",
     *     @OA\Schema(
     *         type="array",
     *         @OA\Items(ref=@Model(type=Customer::class))
     *     )
     * )
    */

     
    public function index(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer)
    {
   
        $page = $request->query->get('page');
        if (is_null($page) || $page < 1) {
            $page = 1;

        $customers = $customerRepository->findAllCustomers($page, 7);
        $json = $serializer->serialize($customers,'json', ['groups' => 'list']);
        $response = new Response($json, 200, [
            "content-type" => "application/json"
        ]);
        return $response;
    }
    }


    /**
    * @Route("/show/{id}", name="customer", methods={"GET"})
    * @ParamConverter("customer", class="App:Customer")
    *      @OA\Response(
    *      response="200",
    *      description="suppression ok",
    * )
    */
    public function show(Customer $customer, CustomerRepository $customerRepository, SerializerInterface $serializer ): Response
    {
        $customer = $customerRepository->find($customer->getId());
        return $this->json($customer, Response::HTTP_OK, [
            'groups' => ['show']
        ]);
        
    }

     /**
     * @Route("/new", name="customer_new", methods={"POST"})
     *      @OA\Response(
     *      response="200",
     *      description="suppression ok",
     * )
     */
    public function newCustomer(Request $request, EntityManagerInterface $entityManager)
    {
        $customer = new Customer();
        $customer->setname($request->get('username'))
            ->setEmail($request->get('password'));
            $this->manager->persist( $customer );
            $this->manager->flush();
            return $customer;
    }

       /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     *      @OA\Response(
     *      response="200",
     *      description="suppression ok",
     * )
     */
    public function deleteCustomer(Customer $customer, EntityManagerInterface $entityManager)
    {
        {
            $entityManager->remove($customer);
            $entityManager->flush();
            return new Response(null, 204);
        }
        throw new HttpException(404, "you can't delete a customer who does not exist !");
    }

}
