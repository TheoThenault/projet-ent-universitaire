<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Formation;
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
        $liste_spe = $entityManagerInterface->getRepository(UE::class)->findAllSpecialite();
        $liste_cur = $entityManagerInterface->getRepository(Cursus::class)->findAllNom();
        $liste_for = $entityManagerInterface->getRepository(Formation::class)->findAllNameOrdered();
        $form = $this->createForm(UEFilterType::class, null, [
            'specialite' => $liste_spe,
            'cursus' => $liste_cur,
            'formation' => $liste_for,
        ]);

        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        $liste_ues = array();

        if($form->isSubmitted() && $form->isValid())
        {
            // le formulaire à été remplit
            $specialite_choisis = $form->get('Specialite')->getData();
            $cursus_choisis = $form->get('Cursus')->getData();
            $formation_choisis = $form->get('Formation')->getData();
            $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAllByChoices($specialite_choisis, $cursus_choisis, $formation_choisis);
        }else{
            // aucun formulaire n'est remplit
            $liste_ues = $entityManagerInterface->getRepository(UE::class)->findAllOrdered();
        }

        dump($liste_ues);

        return $this->render('ue/index.html.twig', [
            'liste_ues' => $liste_ues,
            'ueFormulaire' => $form->createView()
        ]);
    }
}
