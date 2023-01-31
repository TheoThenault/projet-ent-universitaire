<?php

namespace App\EventListener;

use App\Entity\Etudiant;
use App\Entity\Groupe;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Etudiant::class)]
class EtudiantGroupeAutoAfterUpdate
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(Etudiant $etudiant, LifecycleEventArgs $event): void
    {
        // Récupérez tous les groupes de l'entité Groupe
        $groupes = $this->entityManager->getRepository(Groupe::class)->findAll();

        // Appelez la méthode addGroupe de l'entité Etudiant
        $groupes[0]->addEtudiant($etudiant);
//        $etudiant->addGroupe($groupes[0]);
        $this->entityManager->persist($groupes[0]);
        $this->entityManager->flush();
    }
}