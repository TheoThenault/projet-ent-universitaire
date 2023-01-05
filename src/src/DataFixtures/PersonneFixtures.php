<?php

namespace App\DataFixtures;

use App\Entity\Personne;

use Doctrine\Persistence\ObjectManager;

class PersonneFixtures
{
    public array $list_personnes = array();

    public function charger(ObjectManager $manager): void
    {
        $email="univ-poitiers.fr";

        $nom_prenom = array(
            array("Adèle", "Hiriome"),
            array("Ahmed", "Epan"),
            array("Archibald", "Hépompier"),
            array("Bérénice", "Hafoy"),
            array("Bob", "Hinard"),
            array("Bruno", "Zieuvair"),
            array("Carrie", "Danter"),
            array("Célimène", "Kacraké"),
            array("Daisy", "Meuble"),
            array("Danielle", "Nimoit"),
            array("David", "Poche"),
            array("Eléonore", "Cessaint"),
            array("Eugénie", "Desalpage"),
            array("Gary", "Guette"),
            array("Natacha", "Rivari"),
        );

        // ========== CREATION DE PERSONNE ==========
        for ($i = 0; $i <count($nom_prenom); $i++){
            $this->list_personnes[$i] = new Personne();
            $this->list_personnes[$i]
                ->setEmail($nom_prenom[$i][0].".".$nom_prenom[$i][1]."@".$email)
                ->setPrenom($nom_prenom[$i][0])
                ->setNom($nom_prenom[$i][1])
                ->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6')
                ->setRoles(['ROLE_ADMIN']);
            $manager->persist($this->list_personnes[$i]);
        }

        // ========== CREATION ETUDIANT ==========
        $etudiantUser = new Personne();
        $etudiantUser->setRoles(['ROLE_ETUDIANT']);
        $etudiantUser->setEmail("etudiant@" . $email);
        $etudiantUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $etudiantUser->setPrenom('Etudiant');
        $etudiantUser->setNom('User');
        $manager->persist($etudiantUser);

        // ========== CREATION ENSEIGNANT ==========
        $enseignantUser = new Personne();
        $enseignantUser->setRoles(['ROLE_ENSEIGNANT']);
        $enseignantUser->setEmail("enseignant@" . $email);
        $enseignantUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $enseignantUser->setPrenom('Enseignant');
        $enseignantUser->setNom('User');
        $manager->persist($enseignantUser);

        // ========== CREATION ENSEIGNANT RES ==========
        $enseignantResUser = new Personne();
        $enseignantResUser->setRoles(['ROLE_ENSEIGNANT_RES']);
        $enseignantResUser->setEmail("enseignant.res@" . $email);
        $enseignantResUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $enseignantResUser->setPrenom('EnseignantRes');
        $enseignantResUser->setNom('User');
        $manager->persist($enseignantResUser);

        // ========== CREATION SCOLARITE ==========
        $scolariteUser = new Personne();
        $scolariteUser->setRoles(['ROLE_SCOLARITE']);
        $scolariteUser->setEmail("scolarite@" . $email);
        $scolariteUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $scolariteUser->setPrenom('Scolarité');
        $scolariteUser->setNom('User');
        $manager->persist($scolariteUser);

        // ========== CREATION RH ==========
        $rhUser = new Personne();
        $rhUser->setRoles(['ROLE_RH']);
        $rhUser->setEmail("rh@" . $email);
        $rhUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $rhUser->setPrenom('Rh');
        $rhUser->setNom('User');
        $manager->persist($rhUser);

        // ========== CREATION ADMIN ==========
        $adminUser = new Personne();
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setEmail("admin@" . $email);
        $adminUser->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6');
        $adminUser->setPrenom('Admin');
        $adminUser->setNom('User');
        $manager->persist($adminUser);

        $manager->flush();
    }
}
