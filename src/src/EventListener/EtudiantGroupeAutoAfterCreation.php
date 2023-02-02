<?php

namespace App\EventListener;

use App\Entity\Etudiant;
use App\Entity\Groupe;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Etudiant::class)]
class EtudiantGroupeAutoAfterCreation
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postPersist(Etudiant $etudiant, LifecycleEventArgs $event): void
    {
        // Récupérez tous groupes dont les étudiants ont la meme formation que le nouvel étudiant
        $groupesWithSameFormationTD = $this->entityManager->getRepository(Groupe::class)->findAllWithSameFormationAndType($etudiant->getFormation()->getId(), 'TD');
        $groupesWithSameFormationTP = $this->entityManager->getRepository(Groupe::class)->findAllWithSameFormationAndType($etudiant->getFormation()->getId(), 'TP');
        $groupesWithSameFormationCM = $this->entityManager->getRepository(Groupe::class)->findAllWithSameFormationAndType($etudiant->getFormation()->getId(), 'CM');



        // ####### CM
        if(count($groupesWithSameFormationCM) == 0)
        {
            $grpCm = new Groupe();
            $grpCm->addEtudiant($etudiant);
            $grpCm->setType('CM');
            $this->entityManager->persist($grpCm);
        }else{
            $groupesWithSameFormationCM[0]->addEtudiant($etudiant);
        }
        // #######


        // ####### TD
        if(count($groupesWithSameFormationTD) == 0)
        {
            $grpTd = new Groupe();
            $grpTd->addEtudiant($etudiant);
            $grpTd->setType('TD');
            $this->entityManager->persist($grpTd);
        }else{
            $groupFound = 0;
            foreach ($groupesWithSameFormationTD as $groupe)
            {
                $sizeOfGroup = sizeof($groupe->getEtudiants());
                if($sizeOfGroup < 40){
                    $groupe->addEtudiant($etudiant);
                    //$this->entityManager->persist($groupe);
                    $groupFound++;
                }
            }
            if($groupFound == 0){
                $newGrpTP = new Groupe();
                $newGrpTP->addEtudiant($etudiant);
                $newGrpTP->setType('TD');
                $this->entityManager->persist($newGrpTP);
            }
        }
        // ############

        // ########### TP
        if(count($groupesWithSameFormationTP) == 0)
        {
            $grp = new Groupe();
            $grp->addEtudiant($etudiant);
            $grp->setType('TP');
            $this->entityManager->persist($grp);
        }else{
            $groupFound = 0;
            foreach ($groupesWithSameFormationTP as $groupe)
            {
                $sizeOfGroup = sizeof($groupe->getEtudiants());
                if($sizeOfGroup < 20){
                    $groupe->addEtudiant($etudiant);
                    //$this->entityManager->persist($groupe);
                    $groupFound++;
                }
            }
            if($groupFound == 0){
                $newGrpTP = new Groupe();
                $newGrpTP->addEtudiant($etudiant);
                $newGrpTP->setType('TP');
                $this->entityManager->persist($newGrpTP);
            }
        }
        // ###########


        $this->entityManager->flush();
    }
}