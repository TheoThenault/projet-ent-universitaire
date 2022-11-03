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

        for ($i = 0; $i <count($nom_prenom); $i++){
            $this->list_personnes[$i] = new Personne();
            $this->list_personnes[$i]
                ->setEmail($nom_prenom[$i][0].".".$nom_prenom[$i][1]."@".$email)
                ->setPrenom($nom_prenom[$i][0])
                ->setNom($nom_prenom[$i][1]);
            $manager->persist($this->list_personnes[$i]);
        }

        $manager->flush();
    }
}
