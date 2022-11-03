<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Salle;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $batiment = array(
            "a",
            "b",
            "j",
            "cremi",
        );

        $capacite = array(
            "20",
            "30",
            "40",
            '50',
        );

        $equipement = array(
            "informatique",
            "langue",
            "chime",
            "physique",
        );

        $nom = array(
            "1",
            "2",
            "info",
            "1.2",
            "Salut tout le monde c'est la salle"
        );

        $salles = array();
        for($i = 0; $i < 20; $i++){
            $salle[$i] = new Salle();
            $salle[$i]->setBatiment($batiment[rand(0,count($batiment) - 1)])
                ->setCapacite($capacite[rand(0,count($capacite) - 1)])
                ->setEquipement($equipement[rand(0,count($equipement) - 1)])
                ->setNom($nom[rand(0,count($nom) - 1)]);
            $manager->persist($salle[$i]);
        }


        $manager->flush();
    }
}
