<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SalleFixtures;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $salleFixture = new SalleFixtures();
        $salleFixture->charger($manager);

        $manager->flush();
    }
}
