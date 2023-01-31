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

    public function charger(ObjectManager $manager, array $list_enseignant, array $list_salles, array $list_ues, array $list_groupes): void
    {
        $nE = count($list_enseignant);
        $nS = count($list_salles);
        $nU = count($list_ues);

        $nombre_cours = 4000;

        for($i = 0; $i < $nombre_cours; $i++)
        {
            $prof = rand(0, $nE-1);
            $sall = rand(0, $nS-1);
            $_ue = rand(0, $nU-1);

            $cour = new Cour();
            $cour->setCreneau(($i % 600) + 1);
            $cour->setEnseignant($list_enseignant[$prof]);
            $cour->setSalle($list_salles[$sall]);
            $cour->setUe($list_ues[$_ue]);
            for($j = 0; $j < count($list_groupes); $j++)
            {
                if($list_groupes[$j]->getFormation() == $list_ues[$_ue]->getFormation())
                {
                    $cour->setGroupe($list_groupes[$j]);
                    break;
                }
            }
            //$cour->setUe($list_ues[$i % $nU]);
            $this->list_cours[] = $cour;
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
