<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;

class CustomerFixtures extends Fixture  implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i =  0; $i <= 10; $i++) {
            $customer = new Customer();
            $customer->setName($faker->lastName())
                    ->setEmail($faker->email());
            $manager->persist($customer);
            
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
