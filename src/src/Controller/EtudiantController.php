<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Personne;
use App\Form\etudiant\EtudiantAddType;
use App\Form\etudiant\EtudiantEditType;
use App\Form\etudiant\EtudiantFilterType;
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
        }

        return $this->render('etudiant/index.html.twig', [
            'formationFormulaire' => $form->createView(),
            'liste_etudiants' => $liste_etudiants
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantAddType::class, $etudiant);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter un nouvel étudiant']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($etudiant);

            $personne = new Personne();
            $personne->setNom($form->get('nom')->getData());
            $personne->setPrenom($form->get('prenom')->getData());

            $personne->setEmail($personne->getPrenom() . '.' . $personne->getNom() . '@univ-poitiers.fr');

            $personne->setRoles(['ROLE_ETUDIANT']);
            $personne->setEtudiant($etudiant);
            $entityManager->persist($personne);

            $entityManager->flush();
            $this->addFlash('crud', "L'étudiant : {$form->get('prenom')->getData()} {$form->get('nom')->getData()}, a été créé avec succès.");
            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/add.html.twig', [
            'addEtudiantForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function editAction(EntityManagerInterface $em, Request $request, int $id, ): Response
    {
        $etudiant = $em->getRepository(Etudiant::class)->find($id);
        $form = $this->createForm(EtudiantEditType::class, $etudiant);
        $form->get('nom')->setData($etudiant->getPersonne()->getNom());
        $form->get('prenom')->setData($etudiant->getPersonne()->getPrenom());
        $form->add('send', SubmitType::class, ['label' => "Modifier l'étudiant"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etudiant);

            $personne = $etudiant->getPersonne();
            $personne->setNom($form->get('nom')->getData());
            $personne->setPrenom($form->get('prenom')->getData());

            $em->persist($personne);
            $em->flush();

            $this->addFlash('crud', "L'étudiant : {$form->get('prenom')->getData()} {$form->get('nom')->getData()}, a été modifié avec succès.");
            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/edit.html.twig', [
            'editEtudiantForm' => $form->createView(),
        ]);
    }
}
