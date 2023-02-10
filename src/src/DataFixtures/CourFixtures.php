<?php

namespace App\DataFixtures;

use App\Entity\Cour;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Formation;
use App\Entity\Cursus;

class CourFixtures
{
    public array $list_cours = array();

    public function charger(ObjectManager $manager, array $list_enseignant, array $list_salles, array $list_ues): void
    {
        $listHeuresProf = array();

        $manager->flush();
        $list_groupes = $manager->getRepository(Groupe::class)->findAll();

        $nE = count($list_enseignant);
        $nS = count($list_salles);
        $nU = count($list_ues);

        $nombre_cours = 6000;

        for($i = 0; $i < $nombre_cours; $i++)
        {
            $profINDEX = rand(0, $nE-1);
            $prof = $list_enseignant[$profINDEX];

            if(array_key_exists($profINDEX, $listHeuresProf))
            {
                if($listHeuresProf[$profINDEX] >= $prof->getStatutEnseignant()->getNbHeureMax())
                {
                    continue;
                }else{
                    $listHeuresProf[$profINDEX]+=2;
                }
            }else{
                $listHeuresProf[$profINDEX] = 2;
            }

            $sall = rand(0, $nS-1);
            $_ue = rand(0, $nU-1);

            $cour = new Cour();
            $cour->setCreneau(($i % 600) + 1);
            $cour->setEnseignant($prof);
            $cour->setSalle($list_salles[$sall]);
            $cour->setUe($list_ues[$_ue]);
            
            $found = 0;
            $grps = [];
            for($j = 0; $j < count($list_groupes); $j++)
            {
                if($list_groupes[$j]->getFormation() == $list_ues[$_ue]->getFormation())
                {
                    $found++;
                    $grps[] = $list_groupes[$j];                  
                }
            }
            if($found == 0)
            {
                $i--;
                continue;
            }
            $cour->setGroupe($grps[array_rand($grps)]);
            //$cour->setUe($list_ues[$i % $nU]);
            $this->list_cours[] = $cour;
            $manager->persist($cour);
        }

        $manager->flush();
    }
}
