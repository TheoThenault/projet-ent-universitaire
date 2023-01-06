<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\Etudiant;
use App\Entity\Groupe;

class GroupeFixtures
{
    public array $list_groupes = array();

    public function charger(ObjectManager $manager, array $etudiants): void
    {
        $formations_avec_groupe = array();
        for($i = 0; $i < count($etudiants); $i++) {
            //var_dump('i == ' . $i);
            //var_dump(count($etudiants));
            $exist = false;
            for($j = 0; $j < count($formations_avec_groupe); $j++)
            {
                //var_dump($i . '  ' . $j . '   ' . count($formations_avec_groupe));
                if($formations_avec_groupe[$j] == $etudiants[$i]->getFormation())
                {
                    $exist = true;
                    break;
                }
            }
            
            if(!$exist)
            {
                //var_dump('nouveau');
                $grp = new Groupe();
                $grp->setType('TD');
                //$grp->addEtudiant($etudiants[$i]);
                for($j = 0; $j < count($etudiants); $j++)
                {
                    if($etudiants[$i]->getFormation() == $etudiants[$j]->getFormation())
                    {
                        //var_dump('nouvel etudiant ');
                        $grp->addEtudiant($etudiants[$j]);
                    }
                }
                $manager->persist($grp);
                $this->list_groupes[] = $grp;
                $formations_avec_groupe[] = $etudiants[$i]->getFormation();
            }
        }

        $manager->flush();
    }
}
