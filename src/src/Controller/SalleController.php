<?php

namespace App\Controller;

use App\Entity\Salle;
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

    #[Route('/list/{page}', name : 'list', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function listAction(int $page, EntityManagerInterface $em, Request $request): Response{

        if($page < 1) {
            throw $this->createNotFoundException("La page $page n'existe pas");
        }

        //$nbPerPage = $this->getParameter('my_parameter');//Si on utilise une variable globale pour le nombre par page comme dans le tp symfony
        $nbPerPage = 5;

        $form = $this->createForm(SalleType::class);
        $form->add('send', SubmitType::class, ['label' => 'Valider le filtre']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $salles = $em->getRepository(Salle::class)
                ->myFindWithPaging($page, $nbPerPage,$form->getData()["filtre"]);
        }else{
            //form rempli
            $salles = $em->getRepository(Salle::class)
                ->myFindAllWithPaging($page, $nbPerPage);
        }
        $nbTotalPages = intval(ceil(count($salles) / $nbPerPage));

        if($page > $nbTotalPages){
            throw $this->createNotFoundException("La page $page n'existe pas");
        }

        return $this->render('salle/list.html.twig',['salles'=> $salles, 'nbTotalPages'=>$nbTotalPages, 'page'=>$page, 'form' => $form->createView()]);
    }


}