<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SalleFixtures;

use App\DataFixtures\PersonneFixtures;
use App\DataFixtures\EnseignantFixtures;
use App\DataFixtures\StatutEnseignantFixtures;
use App\DataFixtures\UEFixtures;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $salleFixture = new SalleFixtures();
        $salleFixture->charger($manager);

        $personnes_fixture = new PersonneFixtures();
        $personnes_fixture->charger($manager);

        $statuts_enseignant_fixture = new StatutEnseignantFixtures();
        $statuts_enseignant_fixture->charger($manager);

        $enseigants_fixture = new EnseignantFixtures();
        $enseigants_fixture->charger(
            $manager,
            $personnes_fixture->list_personnes,
            $statuts_enseignant_fixture->list_statuts_enseignant
        );


        $cursus_fixture = new CursusFixtures();
        $cursus_fixture->charger($manager);

        $ues = new UEFixtures();
        $ues->charger($manager);

        $manager->flush();
    }
}
