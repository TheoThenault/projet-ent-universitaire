<?php

namespace App\Controller;

use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation', name: 'formation_')]
class FormationController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $liste_formations = array();
        $liste_formations = $entityManagerInterface->getRepository(Formation::class)->findAllOrderedByCursusName();

        return $this->render('formation/index.html.twig', [
            'liste_formations' => $liste_formations
        ]);
    }
}
