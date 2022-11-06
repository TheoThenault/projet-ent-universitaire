<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SalleFixtures;

use App\DataFixtures\PersonneFixtures;
use App\DataFixtures\EnseignantFixtures;
use App\DataFixtures\StatutEnseignantFixtures;
use App\DataFixtures\UEFixtures;
use App\DataFixtures\FormationFixtures;
use App\DataFixtures\SpecialiteFixtures;
use Symfony\Component\Form\Form;

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

        $secialite_fixtures = new SpecialiteFixtures();
        $secialite_fixtures->charger($manager);

        $cursus_fixture = new CursusFixtures();
        $cursus_fixture->charger($manager);

        for($i = 0; $i < count($cursus_fixture->list_cursus); $i++)
        {
            $formation_fixture = new FormationFixtures();
            $formation_fixture->charger($manager, $cursus_fixture->list_cursus[$i]);
        }

        $ues = new UEFixtures();
        $ues->charger($manager);

        $manager->flush();
    }
}
