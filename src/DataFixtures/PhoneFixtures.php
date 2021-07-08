<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Phone;
use Faker;



class PhoneFixtures extends Fixture
{
    private $names = ['i XL240', 's PrimeGalaxy 9', 's Prime Galaxy 11', 'h-p30 lite', 'x redmi Note10', 'x Mi 11 lites', ' g7 ThinQ', ' Qstylus', 'h-p20 dual_sim'];
    private $colors = ['white', 'black', 'gold', 'red', 'blue', 'silver', 'violet'];
    private $memorys = ['32', '64', '128', '256', '512'];
    private $descriptions = [
        'Un concentré technologies pour un mobile à toutes épreuves.Intègre un écran couleur 2,4" (6.1cm)une résolution de 320 x 240 pixels pour un confort de lecture optimal',
        'Reprend le design qui a fait le succès de 2017 Son interface utilisateur intuitif et son écran 2,4 renouvellent ce grand classique. Idéal pour utiliser comme téléphone professionnel',
        'Son processeur Spreadtrum SC6531E, ses 4 Mo RAM et 4 Mo ROM intégrés dans le chipset, vous permettront de naviguer dans les menus ou de téléphoner en toute fluidité.',
        'Votre poche ? Son nouveau lieu d’adoption ! Avec ce feature phone , vous allez avoir un terminal au centre de vos besoins. Réellement ! Il est équipé de toutes les fonctions essentielles pour votre quotidien. Vous allez pouvoir passer vos appels, envoyer vos SMS ou MMS ou bien prendre des photos. 
         Pas besoin d’un téléphone qui en fait des tonnes et qui, grande taille et va répondre à toutes vos attentes. doté de toutes les fonctionnalités fondamentale',
        'Il défie les lois de la pesanteur avec son   poids plume de 71 g ! Il vous accompagne partout sans vous encombrer et sait se faire discret.',
        'Résistant aux chocs, aux sables et à la poussière grâce à son revêtement résistant en uréthane ainsi que sa fermeture par vis. Il vous accompagne dans toutes vos activités sportives ',
        'La vitesse 5G. Une puce A14 Bionic, la plus rapide sur smartphone. Un écran OLED bord à bord. iL multiplie par quatre la résistance aux chutes.'
    ];



    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 40; $i++) {
            $phone = new Phone();
            $phone->setName($this->names[rand(0,8)]);
            $phone->setColor($this->colors[rand(0,6)]);
            $phone->setPrice(rand(500, 1500));
            $phone->setMemory($this->memorys[rand(0,4)]);
            $phone->setDescription($this->descriptions[rand(0,6)]);

            $manager->persist($phone);
        }

        $manager->flush();
    }
    public function getOrder()
    {
        return 3;
    }
}