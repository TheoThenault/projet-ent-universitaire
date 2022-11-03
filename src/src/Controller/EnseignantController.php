<?php

namespace App\Controller;

use App\Entity\Enseignant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/enseignant', name: 'enseignant_')]
class EnseignantController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $enseignants =  $entityManager->getRepository(Enseignant::class)->findAll();

        return $this->render('enseignant/list.twig', [
            'enseignants' => $enseignants,
        ]);
    }
}
