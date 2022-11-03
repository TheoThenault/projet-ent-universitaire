<?php

namespace App\DataFixtures;

use App\Entity\StatutEnseignant;

use Doctrine\Persistence\ObjectManager;

class StatutEnseignantFixtures
{
    public array $list_statuts_enseignant = array();

    public function charger(ObjectManager $manager): void
    {
        $statut_heures = array(
            array("certifié et agrégé", 384),
            array("attaché temporaire", 192),
            array("maitre de conf", 192),
            array("doctorant enseignant", 64),
        );


        for($i = 0; $i<count($statut_heures); $i++) {
            $this->list_statuts_enseignant[$i] = new StatutEnseignant();
            $this->list_statuts_enseignant[$i]
                ->setNom($statut_heures[$i][0])
                ->setNbHeureMin($statut_heures[$i][1])
                ->setNbHeureMax($statut_heures[$i][1]*2);
            $manager->persist($this->list_statuts_enseignant[$i]);
        }

        $manager->flush();
    }
}
