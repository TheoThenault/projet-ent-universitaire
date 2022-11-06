<?php

namespace App\Controller;

use App\Form\UEFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UE;

#[Route('/ue', name: 'ue_')]
class UEController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // récupérer liste des spécialités pour le formulaire
        $liste_specialites = $entityManagerInterface->getRepository(UE::class)->findAllSpecialiteOrdered();
        $form = $this->createForm(UEFilterType::class, null, [
            'specialite' => $liste_specialites,
        ]);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        $liste_ues = array();

        // TODO: modifier la condition lorsque les formulaires seront fait
        if($form->isSubmitted() && $form->isValid())
        {
            // le formulaire à été remplit
            $specialite_choisis = $form->get('Specialite')->getData();
            if($specialite_choisis == 'Tous')
            {
                $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAll();
            }else{
                $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAllBySpecialite($specialite_choisis);
            }
        }else{
            // aucun formulaire n'est remplit
            $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAll();
        }

        dump($liste_ues);

        return $this->render('ue/index.html.twig', [
            'liste_ues' => $liste_ues,
            'ueFormulaire' => $form->createView()
        ]);
    }
}
