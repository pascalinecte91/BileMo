<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Phone;
use Faker;



class PhoneFixtures extends Fixture
{
    private $names = ['iPhone', 'Samsung', 'huawei', 'xiami', 'lg'];
    private $colors = ['white', 'black', 'gold', 'red', 'blue'];
    private $memorys = ['32', '64', '128', '256'];



    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 30; $i++) {
            $phone = new Phone();
            $phone->setName($this->names[rand(0,4)]. ' ' . rand(5, 20));
            $phone->setColor($this->colors[rand(0,4)]);
            $phone->setPrice(rand(500, 1500));
            $phone->setMemory($this->memorys[rand(0,3)]);
            $phone->setDescription($faker->realText() .  ' Android');

            $manager->persist($phone);
        }

        $manager->flush();
    }
}