<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Etudiant;
use App\Entity\Formation;
use App\Form\EtudiantFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiant', name: 'etudiant_')]
class EtudiantController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // récupère la liste des cursus et les niveaux
        $liste_cursus = $entityManagerInterface->getRepository(Etudiant::class)->findAllCursus();
        $liste_formations = $entityManagerInterface->getRepository(Etudiant::class)->findAllFormation();

        $form = $this->createForm(EtudiantFilterType::class, null, [
            'cursus' => $liste_cursus,
            'formation' => $liste_formations,
        ]);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        // par défaut la liste est vide
        $liste_etudiants = array();

        // si par contre, l'utilisateur à déjà remplit un formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            // récupérer ses choix
            $cursus_chosis = $form->get('Cursus')->getData();
            $formation_choisis = $form->get('Formation')->getData();
            $liste_etudiants = $entityManagerInterface->getRepository(Etudiant::class)
                ->findAllByCursusAndFormation($cursus_chosis, $formation_choisis);
        }else{
            $liste_etudiants = $entityManagerInterface->getRepository(Etudiant::class)
                ->findAllByCursusAndFormation('Tous', 'Tous');
        }

        return $this->render('etudiant/index.html.twig', [
            'formationFormulaire' => $form->createView(),
            'liste_etudiants' => $liste_etudiants
        ]);
    }
}
