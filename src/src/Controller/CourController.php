<?php

namespace App\Controller;

use App\Entity\Cour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cour', name: 'cour_')]
class CourController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $liste_cours =  $entityManagerInterface->getRepository(Cour::class)->findAllInformations();
        dump($liste_cours);
        return $this->render('cour/index.html.twig', [
            'liste_cours' => $liste_cours
        ]);
    }
}
