<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/phones")
 */

class PhoneController extends AbstractController
{
    /**
     * @Route("", name="list_phone", methods={"GET"})
     */
    public function index(PhoneRepository $phoneRepository, SerializerInterface $serializer)
    {
      $phones =$phoneRepository->findAll();
      
    
        return $this->json($phones);
    }
}
