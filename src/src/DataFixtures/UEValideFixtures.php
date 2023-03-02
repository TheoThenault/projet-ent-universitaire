<?php

namespace App\DataFixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

use App\Entity\UE;

class UEValideFixtures
{

    public function charger(ObjectManager $manager, array $eleves, array $list_ues, int $nombreUEVALIDE): void
    {
        $ueRepo = $manager->getRepository(UE::class);
        $manager->flush();

        for($i = 0; $i < $nombreUEVALIDE; $i++)
        {
            $eIndex = array_rand($eleves);
            $e = $eleves[$eIndex];
            $ues = $ueRepo->findAllByFormation($e->getFormation()->getId());
            $ueIndex = array_rand($ues);
            $e->addUesValide($ues[$ueIndex]);
            //var_dump($i . '/' . $nombreUEVALIDE);
        }
        $manager->flush();
    }
}
