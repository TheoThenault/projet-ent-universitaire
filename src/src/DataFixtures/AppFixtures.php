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

    private int $nbEtudiants;
    private int $nbEnseignants;
    private int $nbPersonnes;
    private int $nbSalles;
    function __construct() {
        $this->nbPersonnes = 2000;
        $this->nbEtudiants = 1500;
        $this->nbEnseignants = 500;
        $this->nbSalles = 200;
    }
    public function load(ObjectManager $manager): void
    {

        $salleFixture = new SalleFixtures();
        $salleFixture->charger($manager, $this->nbSalles);

        var_dump('salle fini');

        $personnes_fixture = new PersonneFixtures();
        $personnes_fixture->charger($manager, $this->nbPersonnes);

        var_dump('personne fini');

        $statuts_enseignant_fixture = new StatutEnseignantFixtures();
        $statuts_enseignant_fixture->charger($manager);

        var_dump('status_enseignant fini');

        $enseigants_fixture = new EnseignantFixtures();
        $enseigants_fixture->charger(
            $manager,
            $personnes_fixture->list_personnes,
            $statuts_enseignant_fixture->list_statuts_enseignant,
            $this->nbEnseignants
        );

        var_dump('enseignant fini');

        $specialite_fixtures = new SpecialiteFixtures();
        $specialite_fixtures->charger($manager);

        var_dump('specialités fini');

        $cursus_fixture = new CursusFixtures();
        $cursus_fixture->charger($manager);

        var_dump('cursus fini');

        $formation_fixture = new FormationFixtures();
        for($i = 0; $i < count($cursus_fixture->list_cursus); $i++)
        {
            $formation_fixture->charger($manager, $cursus_fixture->list_cursus[$i]);
        }

        var_dump('formations fini');

        $etudiant_fixtures = new EtudiantFixtures();
        $etudiant_fixtures->charger($manager, $personnes_fixture->list_personnes, $this->nbEtudiants, $formation_fixture->list_formations);

        var_dump('etudiants fini');

        $ues = new UEFixtures();
        $ues->charger($manager, $specialite_fixtures->list_specialites, $formation_fixture->list_formations);

        var_dump('ues fini');

        $uesValideFixtures = new UEValideFixtures();
        $uesValideFixtures->charger($manager, $etudiant_fixtures->list_etudiants, $ues->list_ues);

        var_dump('ues valides fini');

        $cours_fixtures = new CourFixtures();
        $cours_fixtures->charger($manager, $enseigants_fixture->list_enseignants, $salleFixture->list_salles,
                                $ues->list_ues);

        var_dump('cours fini');

        $manager->flush();
    }
}
