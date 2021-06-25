<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(SerializerInterface $serializer)
    {
        $phone = new phone();
        $phone->setName('iPhone XS')
              ->setColor('black')
              ->setMemory('32')
              ->setPrice('600')
              ->setDescription('tres bon telephone');

        $data =$serializer->serialize($phone,'json');

        //return $this->json($data);

        return new Response($data, 200,[
            'Content-Type'=>'application/json'
        ]);
    }
}
