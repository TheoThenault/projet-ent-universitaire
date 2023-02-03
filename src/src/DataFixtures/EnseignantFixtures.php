<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;

use App\Entity\Personne;
use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures
{
    public array $list_enseignants = array();

    public function charger(ObjectManager $manager, array $list_personnes, array $list_statuts_enseignant, array $sections, int $nbEnseignants, array $list_formation): void
    {

        // Création d'un enseignant spécifique qui est une personne avec le role ROLE_ENSEIGNANT_RES
        $enseignantResUser = new Enseignant();
        $enseignantResUser->setPersonne(
            $manager->getRepository(Personne::class)->findOneBy(['email' => 'enseignant.res' . "@univ-poitiers.fr"])
        );
        $enseignantResUser->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)])->setSection($sections[array_rand($sections)]);
        $enseignantResUser->setResponsableFormation($list_formation[array_rand($list_formation)]);
        $this->list_enseignants[] = $enseignantResUser;
        $manager->persist($enseignantResUser);

        $manager->flush();

        // ========== CREATION DES ENSEIGNANTS ==========
        for ($i=0; $i<$nbEnseignants && $i < count($list_personnes); $i++){
            $this->list_enseignants[$i] = new Enseignant();

            $formation = $list_formation[array_rand($list_formation)];
            if($formation->getEnseignant() != null)
            {
                $formation = null;
            }
            var_dump($formation->getId());
            $list_personnes[$i]->setRoles(['ROLE_ENSEIGNANT']);
            $this->list_enseignants[$i]
                ->setPersonne($list_personnes[$i])
                ->setStatutEnseignant($list_statuts_enseignant[array_rand($list_statuts_enseignant)])
                ->setSection($sections[array_rand($sections)]);
            if($formation != null){
                $list_personnes[$i]->setRoles(['ROLE_ENSEIGNANT_RES']);
                $this->list_enseignants[$i]->setResponsableFormation($formation);
            }

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
        $manager->flush();


    }
}
