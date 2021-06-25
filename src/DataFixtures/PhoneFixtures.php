<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Phone;
use Faker;
use NumberFormatter;

/*
class PhoneFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $listPhone = ['iphone','samsung','huawei','xioami','lg'],
        $listPhoneColor = ['white','black','gold','red','blue'];
          
        ];
        for ($i = 1; $i <= 30; $i++) {
            $phone = new Phone();

            $listPhone->setName($this->names[random_int(1, 10)])
                ->setColor($this->colors[random_int(0, 4)])
                ->setMemory($faker->randomDigit(3))
                ->setPrice($faker->randomElement(3, 4))
                ->setDescription($faker->realText());
            //insert en db
            $manager->persist($phone);
        }

            $manager->flush();
    }
}
*/

class PhoneFixtures extends Fixture
{
    private $names = ['iPhone', 'Samsung', 'huawei', 'xiami', 'lg'];
    private $colors = ['white', 'black', 'gold', 'red', 'blue'];
    private $memorys = ['32', '64', '128', '256'];

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 30; $i++) {
            $phone = new Phone();
            $phone->setName($this->names[rand(0,4)]. ' ' . rand(5, 20));
            $phone->setColor($this->colors[rand(0,4)]);
            $phone->setPrice(rand(500, 1500));
            $phone->setMemory($this->memorys[rand(0,3)]);
            $phone->setDescription('the best phones ' .  ' Android');

            $manager->persist($phone);
        }

        $manager->flush();
    }
}