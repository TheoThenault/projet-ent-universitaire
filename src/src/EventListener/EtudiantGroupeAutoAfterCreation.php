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
        // Type et effectif max de chaque groupe
        $groupes = [
            'TD' => 40,
            'TP' => 20,
            'CM' => null,
        ];

        // pour chaque Type de groupe
        foreach ($groupes as $type => $limit) {
            // récupérer tous les groupes ayant la même formation que l'étudiant.
            $groupesWithSameFormation = $this->entityManager->getRepository(Groupe::class)->findAllWithSameFormationAndType(
                $etudiant->getFormation()->getId(),
                $type
            );

            $groupFound = 0;
            // Si il reste de la place dans un groupe
            foreach ($groupesWithSameFormation as $groupe) {
                if ($limit === null || sizeof($groupe->getEtudiants()) < $limit) {
                    $groupe->addEtudiant($etudiant);
                    $groupFound++;
                    break;
                }
            }
            // Si il n'y a pas de groupe disponible on en créer un :
            if ($groupFound == 0) {
                $newGrp = new Groupe();
                $newGrp->addEtudiant($etudiant);
                $newGrp->setType($type);
                $this->entityManager->persist($newGrp);
            }
        }

        $this->entityManager->flush();
    }
}