<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

     /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setEmail('pascaline@gmail.com')
            ->setUsername('toto')
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'azerty'
                )
            );

        $manager->persist($user);

        $manager->flush();
    }
}
