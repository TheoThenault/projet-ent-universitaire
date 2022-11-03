<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\UE;

class UEFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ue = new UE();
        $ue->setNom("POO");
        $ue->setVolumeHoraire(75);
        $manager->persist($ue);

        $ue = new UE();
        $ue->setNom("Algorithmes");
        $ue->setVolumeHoraire(75);
        $manager->persist($ue);

        $ue = new UE();
        $ue->setNom("Anglais");
        $ue->setVolumeHoraire(150);
        $manager->persist($ue);

        $ue = new UE();
        $ue->setNom("Droits");
        $ue->setVolumeHoraire(25);
        $manager->persist($ue);

        $ue = new UE();
        $ue->setNom("Web");
        $ue->setVolumeHoraire(75);
        $manager->persist($ue);

        $ue = new UE();
        $ue->setNom("Web avancé");
        $ue->setVolumeHoraire(75);
        $manager->persist($ue);

        $manager->flush();
    }
}
