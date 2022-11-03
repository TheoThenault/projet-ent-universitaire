<?php

namespace App\DataFixtures;

use App\Entity\StatutEnseignant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatutEnseignantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statut_heures = array(
            array("certifié et agrégé", 384),
            array("attaché temporaire", 192),
            array("maitre de conf", 192),
            array("doctorant enseignant", 64),
        );

        $statutsEnseignant = array();
        for($i = 0; $i<count($statut_heures); $i++) {
            $statutsEnseignant[$i] = new StatutEnseignant();
            $statutsEnseignant[$i]
                ->setNom($statut_heures[$i][0])
                ->setNbHeureMin($statut_heures[$i][1])
                ->setNbHeureMax($statut_heures[$i][1]*2);
            $manager->persist($statutsEnseignant[$i]);
        }

        $manager->flush();
    }
}
