<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UEFixtures;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $ues = new UEFixtures();
        $ues->charger($manager);

        for($i = 0; $i < count($ues->list_ues); $i++)
        {
            dump($ues->list_ues[$i]);
        }

        $manager->flush();
    }
}
