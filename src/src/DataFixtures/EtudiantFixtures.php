<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use App\Entity\Personne;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Formation;
use App\Entity\Cursus;

class EtudiantFixtures
{
    public array $list_etudiants = array();

    public function charger(ObjectManager $manager, array $list_personnes, $nombre_etu, array $list_formations): void
    {
        $etudiants_creer = 0;
        for ($index = 0; $etudiants_creer < $nombre_etu && $index < count($list_personnes); $index++) {
            if ($list_personnes[$index]->getEnseignant() == null) {
                $etudiant = new Etudiant();
                $etudiant->setPersonne($list_personnes[$index]->setRoles(['ROLE_ETUDIANT']));
                $etudiant->setFormation($list_formations[$etudiants_creer%count($list_formations)]);
                $manager->persist($etudiant);
                $this->list_etudiants[] = $etudiant;
                $etudiants_creer++;
            }
        }

        // ========== ETUDIANT SPECIFIQUE ==========
        // Création d'un étudiant spécifique qui est une personne avec le role ROLE_ETUDIANT
        // em = entity manager
        $etudiantUser = new Etudiant();
        $etudiantUser->setPersonne(
            $manager->getRepository(Personne::class)->findOneBy(['email' => 'etudiant' . getenv('EMAIL')])
        );
        $etudiantUser->setFormation($list_formations[0]);
        $manager->persist($etudiantUser);
        $this->list_etudiants[] = $etudiantUser;

        // ========== FLUSH ==========

        $manager->flush();
    }
}
