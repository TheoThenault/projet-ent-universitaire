<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\UE;

class UEFixtures
{
    public array $list_ues = array();

    public function charger(ObjectManager $manager, array $specialites): void
    {
        $ue = new UE();
        $ue->setNom("POO");
        $ue->setVolumeHoraire(75);
        $ue->setSpecialite($specialites[26]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $ue = new UE();
        $ue->setNom("Algorithmes");
        $ue->setVolumeHoraire(75);
        $ue->setSpecialite($specialites[26]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $ue = new UE();
        $ue->setNom("Anglais");
        $ue->setVolumeHoraire(150);
        $ue->setSpecialite($specialites[10]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $ue = new UE();
        $ue->setNom("Droits");
        $ue->setVolumeHoraire(25);
        $ue->setSpecialite($specialites[1]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $ue = new UE();
        $ue->setNom("Web");
        $ue->setVolumeHoraire(75);
        $ue->setSpecialite($specialites[26]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $ue = new UE();
        $ue->setNom("Web avancé");
        $ue->setVolumeHoraire(75);
        $ue->setSpecialite($specialites[26]);
        $manager->persist($ue);
        $this->list_ues[] = $ue;

        $manager->flush();
    }
}
