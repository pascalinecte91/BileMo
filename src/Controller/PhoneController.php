<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Form\PhoneType;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;



/**
 * @Route("/phone")
 * 
 */
class PhoneController extends AbstractController
{
    /*
    *@Route("",  name="phone_index", methods={"GET"})
    @Route("/api/{phone}", methods={"GET"})
    *@SWG\Response(
    *     response=200,
    *     @Model(type=User::class)
    * )
    */
    public function index(Request $request, PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {

        $page = $request->query->get('page');
        if (is_null($page) || $page < 1) {
            $page = 1;
        
        
        $phones = $phoneRepository->findAllPhones($page, 5);
        $json = $serializer->serialize($phones,'json', ['groups' => 'list']);
    
        $response = new Response($json, 200, [
            "content-type" => "application/json"
        ]);
        return $response;
    }

}


    /**
     * @Route("/new", name="phone_new", methods={"POST"})
          */
    
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $phone = new Phone();
        $form = $this->createForm(PhoneType::class, $phone);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json(['error' => 'Form bad filled'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($phone);
        $entityManager->flush();

        return $this->json($phone,Response::HTTP_CREATED);
    }

    
    /**
     * @Route("/show/{id}", name="show_phone", methods={"GET"})
     * @ParamConverter("phone", class="App:Phone")
     */
    public function show(Phone $phone, PhoneRepository $phoneRepository, SerializerInterface $serializer): Response
    {
        $phone = $phoneRepository->find($phone->getId());
        return $this->json($phone, Response::HTTP_OK, [
            'groups' => ['show']
        ]);
    }

    /**
     * @Route("/edit/{id}", name="phone_edit", methods={"PUT"})
         */
    public function edit(Request $request, Phone $phone): Response
    {


        $form = $this->createForm(PhoneType::class, $phone);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json(['error' => 'Form bad filled'], Response::HTTP_BAD_REQUEST, [
                'groups' => ['show']
            ]);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json($phone, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="phone_delete", methods={"DELETE"})
     */
    public function delete(Phone $phone, EntityManagerInterface $entityManager)
    {
        {
            $entityManager->remove($phone);
            $entityManager->flush();
            return new Response(null, 204);
            //return $this->json(null, Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * @Route("/update/{id}", name="update_phone", methods={"PUT"})
     */
    public function update(Request $request, SerializerInterface $serializer, Phone $phone, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $phoneUpdate = $entityManager->getRepository(Phone::class)->find($phone->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $phoneUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($phoneUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le téléphone a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }


}
