<?php

namespace App\DataFixtures;

use App\Entity\Etudiant;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Formation;
use App\Entity\Cursus;

class EtudiantFixtures
{
    public array $list_etudiants = array();

    public function charger(ObjectManager $manager, array $list_personnes, $nombre_etu, array $list_formations): void
    {
        $etudiants_creer = 0;
        for($index = 0; $etudiants_creer < $nombre_etu && $index < count($list_personnes); $index++)
        {
            // todo mettre à jour lorsqu'il y aura les autres roles
            // sinon cette condition va tout casser la bdd
            if($list_personnes[$index]->getEnseignant() == null)
            {
                $etudiant = new Etudiant();
                $etudiant->setPersonne($list_personnes[$index]);
                $etudiant->setFormation($list_formations[$etudiants_creer]);
                $manager->persist($etudiant);
                $this->list_etudiants[] = $etudiant;
                $etudiants_creer++;
            }
        }
        $manager->flush();
    }
}
