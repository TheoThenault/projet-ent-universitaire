<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;

use App\Entity\Personne;
use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures
{
    public array $list_enseignants = array();

    public function charger(ObjectManager $manager, array $list_personnes, array $list_statuts_enseignant, array $sections, int $nbEnseignants): void
    {

        // ========== CREATION DES ENSEIGNANTS ==========
        for ($i=0; $i<$nbEnseignants; $i++){
            $this->list_enseignants[$i] = new Enseignant();

            $this->list_enseignants[$i]
                ->setPersonne($list_personnes[$i])
                ->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)])
                ->setSection($sections[array_rand($sections)]);

            $manager->persist($this->list_enseignants[$i]);
        }

        // ========== ENSEIGNANTS SPÉCIFIQUE ==========
        // Création d'un enseignant spécifique qui est une personne avec le role ROLE_ENSEIGNANT
        // em = entity manager
        $enseignantUser = new Enseignant();
        $enseignantUser->setPersonne(
            $manager->getRepository(Personne::class)->findOneBy(['email' => 'enseignant'. "@univ-poitiers.fr"])
        );
        $enseignantUser->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)])->setSection($sections[array_rand($sections)]);
        $this->list_enseignants[] = $enseignantUser;
        $manager->persist($enseignantUser);

        // Création d'un enseignant spécifique qui est une personne avec le role ROLE_ENSEIGNANT_RES
        $enseignantResUser = new Enseignant();
        $enseignantResUser->setPersonne(
            $manager->getRepository(Personne::class)->findOneBy(['email' => 'enseignant.res' . "@univ-poitiers.fr"])
        );
        $enseignantResUser->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)])->setSection($sections[array_rand($sections)]);
        $this->list_enseignants[] = $enseignantResUser;
        $manager->persist($enseignantResUser);

        $manager->flush();
    }
}
