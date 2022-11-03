<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;
use App\Entity\Personne;
use App\Entity\StatutEnseignant;

use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures
{
    public function charger(ObjectManager $manager): void
    {
        $nb_enseignant = 6;

        $personnes = $manager->getRepository(Personne::class)->findAll();
        $statutsEnseignant = $manager->getRepository(StatutEnseignant::class)->findAll();

        $enseignants = array();
        for ($i=0; $i<$nb_enseignant; $i++){
            $enseignants[$i] = new Enseignant();
            $enseignants[$i]
                ->setPersonne($personnes[$i])
                ->setStatutEnseignant(array_rand($statutsEnseignant, 1));
            $manager->persist($enseignants[$i]);
        }

        $manager->flush();
    }
}
