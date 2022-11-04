<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\Cursus;

class CursusFixtures
{
    public array $list_cursus = array();

    public function charger(ObjectManager $manager): void
    {
        $cursus = new Cursus();
        $cursus->setNiveau("Licence");
        $cursus->setNom("Informatique");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Master");
        $cursus->setNom("Informatique");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Licence");
        $cursus->setNom("Literraire");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Master");
        $cursus->setNom("Literraire");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Licence");
        $cursus->setNom("Droit");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Licence");
        $cursus->setNom("Médecine");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $cursus = new Cursus();
        $cursus->setNiveau("Master");
        $cursus->setNom("Médecine");
        $manager->persist($cursus);
        $this->list_cursus[] = $cursus;

        $manager->flush();
    }
}
