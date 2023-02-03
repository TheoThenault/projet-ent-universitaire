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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/etudiant', name: 'etudiant_')]
class EtudiantController extends AbstractController
{
	#[Route('/{nPage}', name: 'index',
		requirements: ['nPage' => '\d+'],
		defaults:     ['nPage' => 1]
	)]
    public function index($nPage, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if($nPage <= 0)
	{
		throw new NotFoundHttpException('La Page n\'existe pas');
	}

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
        
        $pageMax = 0;

        // si par contre, l'utilisateur à déjà remplit un formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            $perPage = $this->getParameter('lignes_par_page');  
     
	     // récupérer ses choix
            $cursus_chosis = $form->get('Cursus')->getData();
            $formation_choisis = $form->get('Formation')->getData();
            $entries = $form->get('Entry')->getData();
            $arrayEntries = explode(' ', $entries);
            if(!($arrayEntries[0]=='' && $cursus_chosis == 'Tous' && $formation_choisis == 'Tous'))
            {
                $liste_etudiants = $entityManagerInterface->getRepository(Etudiant::class)
                ->findAllByCursusAndFormationPaged($arrayEntries,$cursus_chosis, $formation_choisis, $nPage, $perPage);
               $pageMax = intval(ceil(count($liste_etudiants)/$perPage));
               if($nPage != 1 && $nPage > $pageMax)  // Différent de 1 car si la BDD est vide on veut quand même afficher une page basique pour l'utilisateur
               {
                   throw new NotFoundHttpException('La page n\'existe pas');
               }
            }
        }

        return $this->render('etudiant/index.html.twig', [
            'formationFormulaire' => $form->createView(),
            'liste_etudiants' => $liste_etudiants,
            'currPage' => $nPage,
            'pageMax' => $pageMax
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addAction(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): Response
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

            $personne->setEmail($personne->getPrenom() . '.' . $personne->getNom() . "@univ-poitiers.fr");

            $personne->setRoles(['ROLE_ETUDIANT']);
            $personne->setEtudiant($etudiant);

            $errors = $validator->validate($personne);
            if(count($errors) <= 0){
                $entityManager->persist($personne);

                $entityManager->flush();
                $this->addFlash('crud', "L'étudiant : {$form->get('prenom')->getData()} {$form->get('nom')->getData()}, a été créé avec succès.");
                return $this->redirectToRoute('etudiant_index');
            } else {
                $errorsString = (string) $errors;
                return $this->render('etudiant/add.html.twig', [
                    'addEtudiantForm' => $form->createView(),
                    'errors' => $errors,
                ]);
            }
        }

        return $this->render('etudiant/add.html.twig', [
            'addEtudiantForm' => $form->createView(),
            'errors' => [],
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
