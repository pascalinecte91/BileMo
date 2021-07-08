<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    private $name = ['orange', 'sfr', 'bouygues', 'free', 'virgin'];
    /**
    * @var UserPasswordHasherInterface
    */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for($i =  0; $i< 5; $i++){
        $customer = new Customer();
        $customer->setName($this->name[rand(0,4)])
                ->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPassword(
                    $this->passwordHasher->hashPassword($customer,'azerty' )
                );
        $manager->persist($customer);
    }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
