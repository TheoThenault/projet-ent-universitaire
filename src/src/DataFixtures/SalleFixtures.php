<?php

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Salle;

class SalleFixtures{

    public array $list_salles = array();

    public function charger(ObjectManager $manager, int $nbSalles): void
    {
        $batiment = array(
            "H01",
            "B02",
            "G03",
            "H02",
        );

        $capacite = array(
            20,
            30,
            40,
            50,
            100,
        );

        $equipement = array(
            "informatique",
            "langue",
            "chime",
            "physique",
        );

        for($i = 0; $i < $nbSalles; $i++){
            $this->list_salles[$i] = new Salle();
            $b = $batiment[rand(0,count($batiment) - 1)];
            $n = $b . " " . $i;
            $this->list_salles[$i]
                ->setBatiment($b)
                ->setCapacite($capacite[rand(0,count($capacite) - 1)])
                ->setEquipement($equipement[rand(0,count($equipement) - 1)])
                ->setNom($n);
            $manager->persist($this->list_salles[$i]);
        }

        $manager->flush();
    }
}
