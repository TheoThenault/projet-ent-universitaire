<?php

namespace App\DataFixtures;

use App\Entity\Cour;
use App\Entity\Etudiant;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Formation;
use App\Entity\Cursus;

class CourFixtures
{
    public array $list_cours = array();

    public function charger(ObjectManager $manager, array $list_enseignant, array $list_salles, array $list_ues): void
    {
        $nE = count($list_enseignant);
        $nS = count($list_salles);
        $nU = count($list_ues);

        $nombre_cours = 40;

        for($i = 0; $i < $nombre_cours; $i++)
        {
            $cour = new Cour();
            $cour->setCreneau(($i % 40) + 1);
            $cour->setEnseignant($list_enseignant[$i % $nE]);
            $cour->setSalle($list_salles[$i%$nS]);
            $cour->setUe($list_ues[1]);
            //$cour->setUe($list_ues[$i % $nU]);
            $this->list_cours[] = $cour;
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
