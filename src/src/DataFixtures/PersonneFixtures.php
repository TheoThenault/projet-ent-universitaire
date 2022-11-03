<?php

namespace App\DataFixtures;

use App\Entity\Personne;

use Doctrine\Persistence\ObjectManager;

class PersonneFixtures
{

    public function load(ObjectManager $manager): void
    {
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
        $email="univ-poitiers.fr";
        $personnes = array();

        for ($i = 0; $i <count($nom_prenom); $i++){
            $personnes[$i] = new Personne();
            $personnes[$i]
                ->setEmail($nom_prenom[$i][0].".".$nom_prenom[$i][1]."@".$email)
                ->setPrenom($nom_prenom[$i][0])
                ->setNom($nom_prenom[$i][1]);
            $manager->persist($personnes[$i]);
        }

        $manager->flush();
    }
}
