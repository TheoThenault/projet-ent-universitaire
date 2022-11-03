<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;

use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures
{
    public array $list_enseignants = array();

    public function charger(ObjectManager $manager, array $list_personnes, array $list_statuts_enseignant): void
    {
        $nb_enseignant = 6;

        for ($i=0; $i<$nb_enseignant; $i++){
            $this->list_enseignants[$i] = new Enseignant();

            $this->list_enseignants[$i]
                ->setPersonne($list_personnes[$i])
                ->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)]);

            $manager->persist($this->list_enseignants[$i]);
        }

        $manager->flush();
    }
}
