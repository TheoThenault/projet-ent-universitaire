<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Formation;
use App\Form\formation\FormationAddType;
use App\Form\formation\FormationFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation', name: 'formation_')]
class FormationController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // récupère la liste des cursus
        $liste_cursus = $entityManagerInterface->getRepository(Cursus::class)->findAllNomEtNiveau();

        dump($liste_cursus);

        $form = $this->createForm(FormationFilterType::class, null, [
                'cursus_name' => $liste_cursus['noms'],
                'cursus_niveau' => $liste_cursus['niveaux'],
            ]);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        // par défaut la liste est vide
        $liste_formations = array();

        // si par contre, l'utilisateur à déjà remplit un formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            // récupérer ses choix
            $cursus_choisis_name = $form->get('CursusName')->getData();
            $cursus_choisis_niv = $form->get('CursusNiveau')->getData();
            $liste_formations = $entityManagerInterface->getRepository(Formation::class)
                ->findAllByCursusNameAndNiveau($cursus_choisis_name, $cursus_choisis_niv);
        }


        return $this->render('formation/index.html.twig', [
            'formationFormulaire' => $form->createView(),
            'liste_formations' => $liste_formations
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationAddType::class, $formation);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter une nouvelle formation']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($formation);
            $entityManager->flush();

            $this->addFlash('crud', "La formation : {$form->get('nom')->getData()}, a été créée avec succès.");
            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/add.html.twig', [
            'addFormationForm' => $form->createView(),
        ]);
    }
}
