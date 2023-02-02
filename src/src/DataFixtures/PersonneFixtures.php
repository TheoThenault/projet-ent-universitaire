<?php

namespace App\DataFixtures;

use App\Entity\Personne;

use Doctrine\Persistence\ObjectManager;

class PersonneFixtures
{
    public array $list_personnes = array();

    private function getNom(): string
    {
        $noms = [
            'Martin', 'Simon', 'Morel', 'Legrand', 'Perrin', 'Bernard', 'Laurent', 'Girard', 'Garnier',
            'Morin', 'Dubois', 'Lefebvre', 'Andre', 'Faure', 'Dupont', 'Fontaine', 'Lopez', 'Robin',
            'Leroy', 'Durand', 'Petit', 'Bertrand', 'Richard', 'Poirier', 'Rideau', 'Merlu', 'Duval',
            'Brun', 'Noel', 'Sins', 'Gourdin', 'Rhoades', 'Melon', 'Guerin', 'Nicolas', 'Leclerc',
            'Laporte', 'Lemaitre', 'Langlois', 'Breton', 'Leroux', 'Charles', 'Bonnet', 'Dubois', 'Deschamps',
            'Kenobi', 'Potter', 'Fujiwara', 'Usumaki', 'Willis', 'Cruise'
        ];
        $index =  rand(0, count($noms)-1);
        return $noms[$index];
    }

    private function getPrenom(): string
    {
        $prenoms = [
            'Mattieu', 'Jean', 'Pierre', 'Michel', 'Sasha', 'André', 'Philippe', 'Olivier', 'Bernard',
            'Marie', 'Jeanne', 'Monique', 'Isabelle', 'Nathalie', 'Sylvie', 'Suzanne', 'Abella', 'Lana',
            'Johnny', 'Camille', 'Roger', 'Paul', 'Daniel', 'Henri', 'Nicolas', 'Manuel', 'Jacques',
            'Mia', 'Sarah', 'Rose', 'Jade', 'Emma', 'Angele', 'Léa', 'Manon', 'Lucie', 'Clara',
            'Alexandre', 'Hugo', 'Lucas', 'Théo', 'Simon', 'Quentin', 'Mathis', 'Paul', 'Bastien',
            'Amélie', 'Alicia', 'Carla', 'Elisa', 'Margaux', 'Mélissa', 'Léna', 'Elise', 'Ambre',
            'Bruce', 'Takumi', 'Harry', 'Obiwan', 'Anakin', 'Qui-Gon', 'Tom'
        ];
        $index =  rand(0, count($prenoms)-1);
        return $prenoms[$index];
    }

    public function charger(ObjectManager $manager): void
    {
        $email="univ-poitiers.fr";

        $nombre_personnes = 10000;

        // ========== CREATION DE PERSONNE ==========
        for ($i = 0; $i < $nombre_personnes; $i++){
            $prenom = $this->getPrenom();
            $nom = $this->getNom();
            $this->list_personnes[$i] = new Personne();
            $this->list_personnes[$i]
                ->setEmailSafe($nom.".".$prenom, $manager)
                ->setPrenom($prenom)
                ->setNom($nom)
                ->setPassword('$2y$13$PQfkvYMxBXDalJ5hP9kilue8jeJarc3wGnCwvtzxg7noPPYOIZCv6')
                ->setRoles(['ROLE_USER']);
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
