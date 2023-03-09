<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Form\cursus\CursusAddType;
use App\Form\cursus\CursusEditType;
use App\Form\cursus\CursusFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cursus', name: 'cursus_')]
class CursusController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // récupérer la liste des niveau
        $liste_niveaux = $entityManagerInterface->getRepository(Cursus::class)->findAllNiveaux();

        $form = $this->createForm(CursusFilterType::class, null, ['niveaux' => $liste_niveaux]);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);


        // par défaut la liste de cursus est vide si, l'utilisateur vient de se connecter sur la page par exemple
        $liste_cursus = array();

        // si par contre, l'utilisateur à déjà remplit un formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            // récupérer ses choix
            $niveau_choisis = $form->get('Niveau')->getData();

            // requete sql en fonction du choix
            if($niveau_choisis == 'Tous')
            {
                $liste_cursus = $entityManagerInterface->getRepository(Cursus::class)->findAllOrdered();
            }else{
                $liste_cursus = $entityManagerInterface->getRepository(Cursus::class)->findBy(['niveau' => $niveau_choisis]);
            }
        }

        // afficher la page
        return $this->render('cursus/index.html.twig', [
            'cursusFormulaire' => $form->createView(),
            'liste_cursus' => $liste_cursus
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $cursus = new Cursus();
        $form = $this->createForm(CursusAddType::class, $cursus);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter un nouveau cursus']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($cursus);
            $entityManager->flush();
            $this->addFlash('crud', "Le cursus : {$form->get('nom')->getData()}, a été créé avec succès.");
            return $this->redirectToRoute('cursus_index');
        }

        return $this->render('cursus/add.html.twig', [
            'addCursusForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function editAction($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $cursus = $entityManager->getRepository(Cursus::class)->find($id);
        if($cursus == null){
            $this->addFlash('crud', "Le cursus n'existe pas.");
            return $this->redirectToRoute('ue_index');
        }
        $form = $this->createForm(CursusEditType::class, $cursus);
        $form->add('send',SubmitType::class,['label'=>'Modifier']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($cursus);
            $entityManager->flush();

            $this->addFlash('crud', "Le cursus : {$cursus->getNom()} a été modifiée avec succès.");
            return $this->redirectToRoute('cursus_index');
        }
        return $this->render('cursus/edit.html.twig',
            ['editCursusForm'=>$form->createView(), 'id'=>$id]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deleteAction($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $cursus = $entityManager->getRepository(Cursus::class)->find($id);
        if($cursus == null){
            $this->addFlash('crud', "Le cursus n'existe pas.");
            return $this->redirectToRoute('cursus_index');
        }
        $entityManager->remove($cursus);
        $entityManager->flush();

        $this->addFlash('crud', "Le cursus : {$cursus->getNom()} a été supprimée avec succès.");
        return $this->redirectToRoute('cursus_index');
    }
}
