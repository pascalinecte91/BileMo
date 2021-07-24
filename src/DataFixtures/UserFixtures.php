<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    private $namesU = ['orange', 'sfr', 'bouygues', 'free', 'virgin'];


    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $user = new User();
        $user->setEmail('pascaline@gmail.com')
            ->setName('toto')
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'azerty'
                )
            );
        $manager->persist($user);

        for ($u =  0; $u <= 4; $u++) {
            $user = new User();
            $user->setName($this->namesU[$u])
                ->setEmail($faker->email())
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'azerty'));

            $manager->persist($user);
            $this->addReference('user_' . $u, $user);
        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 1;
    }
}
