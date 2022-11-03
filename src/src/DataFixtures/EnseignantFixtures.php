<?php

namespace App\DataFixtures;

use App\Entity\Enseignant;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EnseignantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $personne = new Personne();
        $personne
            ->setEmail('gaelle.skapin@univ.fr')
            ->setNom('Skapin')
            ->setPrenom('Gaelle');
        $manager->persist($personne);

        $enseignant = new Enseignant();
        $enseignant
            ->setPersonne($personne);

        $manager->persist($enseignant);

        $manager->flush();
    }
}
