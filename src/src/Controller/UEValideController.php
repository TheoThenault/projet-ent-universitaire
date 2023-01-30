<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ueValide', name: 'ueValide_')]
class UEValideController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->getUser()->getEtudiant() == null)
        {
            return $this->render('ue_valide/index.html.twig', ['pasEtudiant' => 42]);
        }
        $etudiantID = $this->getUser()->getEtudiant()->getId();
        $uesValides = $this->getUser()->getEtudiant()->getUesValides();
        dump($this->getUser());
        dump($etudiantID);
        dump($uesValides);
        return $this->render('ue_valide/index.html.twig');
    }

}
