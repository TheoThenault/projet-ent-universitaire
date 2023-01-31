<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\salle\SalleAddType;
use App\Form\salle\SalleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salle', name: 'salle_')]
class SalleController extends AbstractController{
    #[Route('', name: 'index')]
    public function indexAction(EntityManagerInterface $em, Request $request): Response
    {

        $liste_bat = $em->getRepository(Salle::class)->findAllBatiment();
        $liste_equip = $em->getRepository(Salle::class)->findAllEquipement();
        $liste_capacite = $em->getRepository(Salle::class)->findAllCapacite();
        $form = $this->createForm(SalleType::class, null, [
            'batiment' => $liste_bat,
            'equipement' => $liste_equip,
            'capacite' => $liste_capacite,
        ]);

        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        $liste_salle = array();

        if ($form->isSubmitted() && $form->isValid()) {
            $batiment_choisis = $form->get('Batiment')->getData();
            $equipement_choisis = $form->get('Equipement')->getData();
            $capacite_choisis = $form->get('Capacite')->getData();
            $liste_salle = $em->getRepository(Salle::class)->findAllByChoices($batiment_choisis, $equipement_choisis, $capacite_choisis);
        }


        return $this->render('salle/index.html.twig', [
            'liste_salle' => $liste_salle,
            'form' => $form->createView()
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addAction(EntityManagerInterface $entityManager, Request $request): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleAddType::class, $salle);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter une nouvelle salle']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('salle_index');
        }

        $this->addFlash('crud', "La salle : {$form->get('nom')->getData()}, a été créée avec succès.");
        return $this->render('salle/add.html.twig', [
            'addSalleForm' => $form->createView(),
        ]);
    }

}