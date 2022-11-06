<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\Formation;
use App\Entity\Cursus;

class FormationFixtures
{
    public array $list_formations = array();

    public function charger(ObjectManager $manager, Cursus $cursus): void
    {
        if($cursus->getNiveau() == "Licence")
        {
            $formation = new Formation();
            $formation->setNom("Licence 1");
            $formation->setAnnee(1);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;

            $formation = new Formation();
            $formation->setNom("Licence 2");
            $formation->setAnnee(2);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;

            $formation = new Formation();
            $formation->setNom("Licence 3");
            $formation->setAnnee(3);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;

            $formation = new Formation();
            $formation->setNom("Licence 3 PRO");
            $formation->setAnnee(3);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;
        }

        if($cursus->getNiveau() == "Master")
        {
            $formation = new Formation();
            $formation->setNom("Master 1");
            $formation->setAnnee(1);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;

            $formation = new Formation();
            $formation->setNom("Master 2");
            $formation->setAnnee(2);
            $formation->setCursus($cursus);
            $manager->persist($formation);
            $this->list_formations[] = $formation;
        }

        $manager->flush();
    }
}
