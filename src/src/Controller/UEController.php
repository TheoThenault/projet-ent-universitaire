<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UE;

#[Route('/ue', name: 'ue_')]
class UEController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $liste_ues = array();

        // TODO: modifier la condition lorsque les formulaires seront fait
        if(true) {
            // aucun formulaire n'est remplit
            $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAll();
        }else{
            // le formulaire à été remplit
        }


        return $this->render('ue/index.html.twig', [
            'liste_ues' => $liste_ues,
        ]);
    }
}
