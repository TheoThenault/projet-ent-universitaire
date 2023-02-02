<?php

namespace App\DataFixtures;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

use App\Entity\UE;

class UEValideFixtures
{

    public function charger(ObjectManager $manager, array $eleves, array $list_ues): void
    {
        $ueRepo = $manager->getRepository(UE::class);
        $manager->flush();
        foreach ($eleves as $e)
        {
            $ues = $e->getFormation()->getUes();
            if(count($ues) > 0)
            {
                $index = rand(0, count($ues)-1);
                $e->addUesValide($ues[$index]);
            }
        }
        $manager->flush();
    }
}
