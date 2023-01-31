<?php

namespace App\DataFixtures;

use App\DataFixtures\FormationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SalleFixtures;

use App\DataFixtures\PersonneFixtures;
use App\DataFixtures\EnseignantFixtures;
use App\DataFixtures\StatutEnseignantFixtures;
use App\DataFixtures\GroupeFixtures;
use App\DataFixtures\UEFixtures;
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

        $specialite_fixtures = new SpecialiteFixtures();
        $specialite_fixtures->charger($manager);

        $cursus_fixture = new CursusFixtures();
        $cursus_fixture->charger($manager);

        $formation_fixture = new FormationFixtures();
        for($i = 0; $i < count($cursus_fixture->list_cursus); $i++)
        {
            $formation_fixture->charger($manager, $cursus_fixture->list_cursus[$i]);
        }

        $etudiant_fixtures = new EtudiantFixtures();
        $etudiant_fixtures->charger($manager, $personnes_fixture->list_personnes, 1000, $formation_fixture->list_formations);

        // groupes fixtures
        $groupe_fixtures = new GroupeFixtures();
        $groupe_fixtures->charger($manager, $etudiant_fixtures->list_etudiants);

        $ues = new UEFixtures();
        $ues->charger($manager, $specialite_fixtures->list_specialites, $formation_fixture->list_formations);

        $uesValideFixtures = new UEValideFixtures();
        $uesValideFixtures->charger($manager, $etudiant_fixtures->list_etudiants, $ues->list_ues);

        $cours_fixtures = new CourFixtures();
        $cours_fixtures->charger($manager, $enseigants_fixture->list_enseignants, $salleFixture->list_salles,
                                $ues->list_ues, $groupe_fixtures->list_groupes);

        $manager->flush();
    }
}
