<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/phone")
 */

class PhoneController extends AbstractController
{
    /**
     * @Route("", name="phone_index", methods={"GET"})
     */
    public function index(PhoneRepository $phoneRepository): Response
    {
      $phones =$phoneRepository->findAll();
      
        return $this->json($phones, Response::HTTP_OK,[], [
            'groups' => [
                'phone:list'
            ]
        ] );
    }


    /**
 * @Route("", name="phone_new", methods={"POST"})
 */
public function new(Request $request): Response
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

    return $this->json($phone, Response::HTTP_CREATED, [], [
        'groups' => [
            'phone:show']
    ]);
}
  /**
     * @Route("/{id}", name="phone_show", methods={"GET"})
     */
    public function show(Phone $phone): Response
    {
        return $this->json($phone, Response::HTTP_OK, [], [
            'groups' => [
                'phone:show']
        ]);
    }

    /**
     * @Route("/{id}", name="phone_edit", methods={"PUT"})
     */
    public function edit(Request $request, Phone $phone): Response
    {
        $form = $this->createForm(PhoneType::class, $phone);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json(['error' => 'Form bad filled'], Response::HTTP_BAD_REQUEST);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->json($phone, Response::HTTP_OK, [], [
            'groups' => [
                'phone:show',
            ]
        ]);
    }

     /**
     * @Route("/{id}", name="phone_delete", methods={"DELETE"})
     */
    public function delete(Phone $phone): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phone);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
