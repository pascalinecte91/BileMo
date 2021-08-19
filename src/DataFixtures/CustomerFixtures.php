<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Faker\Factory;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i =  0; $i <= 50; $i++) {
            $user = $this->getReference('user_' . ($i % 6));

            $customer = new Customer();
            $customer->setName($faker->lastName())
                ->setEmail($faker->email())
                ->setUser($user);

            $manager->persist($customer);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
