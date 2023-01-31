<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

use App\Entity\UE;

class UEValideFixtures
{

    private function getUes($formationId, array $ues): array
    {
        $res = [];

        foreach ($ues as $ue)
        {
            if($ue->getFormation()->getId() == $formationId)
            {
                $res[] = $ue;
            }
        }

        return $res;
    }

    public function charger(ObjectManager $manager, array $eleves, array $list_ues): void
    {
        $manager->flush();
        foreach ($eleves as $e)
        {
            $ues = $this->getUes($e->getFormation()->getId(), $list_ues);
            //var_dump($e->getFormation()->getNom());
            //var_dump($ues[0]->getNom());
            if(count($ues) > 0)
            {
                $index = rand(0, count($ues)-1);
                $e->addUesValide($ues[$index]);
            }
        }
        $manager->flush();
    }
}
