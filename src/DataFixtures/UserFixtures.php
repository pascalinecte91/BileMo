<?php

namespace App\DataFixtures;

use Faker\Factory;
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
       /* $faker = Factory::create('fr-FR');


        for($u =  0; $u < 10; $u++){
        $user = new User();
        $user->setEmail($faker->email())
            ->setUsername($faker->firstName())
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,'azerty'));*/
        $user = new User();
        $user->setEmail('pascaline@gmail.com')
            ->setUsername('toto')
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'azerty'));

        $manager->persist($user);

        //$this->addReference('user_' . $u, $user);
    
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
