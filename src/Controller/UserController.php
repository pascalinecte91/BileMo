<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
use Swagger\Annotations as SWG;


/**
 * @Route("/user")
 * 
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
     
    }

    public function show(User $user): User
    {
            return$user;
    }

     /**
     * @Route("", name="user_new", methods={"POST"})
     */
    public function newUser(Request $request)
    {
        $user = new User();
        $user->setname($request->get('username'))
            ->setPassword('password')
            ->setEmail($request->get('password'));
            $this->manager->persist( $user );
            $this->manager->flush();
            return $user;
    }

       /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        {
            $entityManager->remove($user);
            $entityManager->flush();
            return new Response(null, 204);
        }
        throw new HttpException(404, "you can't delete a user who does not exist !");
    }

}
