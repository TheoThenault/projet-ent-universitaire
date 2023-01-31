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
        $groupesWithSameFormation = $this->entityManager->getRepository(Groupe::class)->findAllWithSameFormation($etudiant->getFormation()->getId());

        // Si il n'y a aucun de groupe avec la meme formation alors on en créer trois
        if (!$groupesWithSameFormation){
            $grpTd = new Groupe();
            $grpTd->addEtudiant($etudiant);
            $grpTd->setType('TD');
            $this->entityManager->persist($grpTd);

            $grpTp = new Groupe();
            $grpTp->addEtudiant($etudiant);
            $grpTp->setType('TP');
            $this->entityManager->persist($grpTp);

            $grpCm = new Groupe();
            $grpCm->addEtudiant($etudiant);
            $grpCm->setType('CM');
            $this->entityManager->persist($grpCm);
        } else {
            $grpsTp = [];
            $grpsTd = [];

            foreach ($groupesWithSameFormation as $groupe) {
                // add student to CM. NO MAX
                if($groupe->getType() ==  'CM'){
                    $groupe->addEtudiant($etudiant);
                    $this->entityManager->persist($groupe);
                }
                // add all TD groupe to $grpsTp
                if($groupe->getType() ==  'TD'){
                    $grpsTd[] = $groupe;
                }
                // add all TP groupe to $grpsTd
                if($groupe->getType() ==  'TP'){
                    $grpsTp[] = $groupe;
                }
            }

            // add student to TP. MAX = 20
            $groupTpFound = 0;
            foreach ($grpsTp as $groupe){
                if(sizeof($groupe->getEtudiants())<20){
                    $groupe->addEtudiant($etudiant);
                    $this->entityManager->persist($groupe);
                    $groupTpFound++;
                }
            }
            if($groupTpFound == 0){
                $newGrpTP = new Groupe();
                $newGrpTP->addEtudiant($etudiant);
                $newGrpTP->setType('TP');
                $this->entityManager->persist($newGrpTP);
            }

            // add student to TD MAX = 40
            $groupTdFound = 0;
            foreach ($grpsTd as $groupe){
                if(sizeof($groupe->getEtudiants())<40){
                    $groupe->addEtudiant($etudiant);
                    $this->entityManager->persist($groupe);
                    $groupTdFound++;
                }
            }
            if($groupTdFound == 0){
                $newGrpTD = new Groupe();
                $newGrpTD->addEtudiant($etudiant);
                $newGrpTD->setType('TD');
                $this->entityManager->persist($newGrpTD);
            }
        }
        $this->entityManager->flush();
    }
}