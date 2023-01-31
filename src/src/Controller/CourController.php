<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Cursus;
use App\Entity\Formation;
use App\Entity\Enseignant;
use App\Form\cour\CourFilterType;
use App\Form\cour\CourEtuDateFilterType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cour', name: 'cour_')]
class CourController extends AbstractController
{

    private function getNow() : DateTime
    {
        $date = new DateTime();
        try {
            $date = new DateTime('now', null);
        } catch (Exception $e) {
        }
        return $date;
    }


    #[Route('', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        if(!$this->isGranted('ROLE_ETUDIANT')){
            $liste_cur = $entityManagerInterface->getRepository(Cursus::class)->findAllNom();
            $liste_for = $entityManagerInterface->getRepository(Formation::class)->findAllNameOrdered();
            $liste_enstmp = $entityManagerInterface->getRepository(Enseignant::class)->sortByNameAscOrDesc('ASC');
            $liste_ens['Tous'] = 'Tous';
            for($i = 0; $i < count($liste_enstmp); $i++)
            {
                $nom = $liste_enstmp[$i]->getPersonne()->getNom() . ' ' . $liste_enstmp[$i]->getPersonne()->getPrenom();
                $liste_ens[$nom] = $liste_enstmp[$i]->getPersonne()->getNom();
            }

            $form = $this->createForm(CourFilterType::class, null, [
                'cursus' => $liste_cur,
                'formation' => $liste_for,
                'enseignant' => $liste_ens
            ]);
            $form->add('Filtrer', SubmitType::class);

            $form->handleRequest($request);
            $liste_cours = array();

            if($form->isSubmitted() && $form->isValid())
            {
                // le formulaire à été remplit
                $date_choisis = $form->get('Semaine')->getData();
                //dump($date_choisis);
                $cursus_choisis = $form->get('Cursus')->getData();
                $formation_choisis = $form->get('Formation')->getData();
                $prof_choisis = $form->get('Enseignant')->getData();
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus_choisis, $formation_choisis, $date_choisis, $prof_choisis);
            }

            //dump($liste_cours);
            return $this->render('cour/index.html.twig', [
                'liste_cours' => $liste_cours,
                'form' => $form->createView()
            ]);


        }
        else{//Pour étudiant
            $form = $this->createForm(CourEtuDateFilterType::class);
            $form->add('Valider', SubmitType::class);
            $form->add("Date_du_jour", SubmitType::class, ['label' => "Aujourd'hui",]);

            $form->handleRequest($request);
            $liste_cours = array();

            $formation = $this->getUser()->getEtudiant()->getFormation()->getNom();
            $cursus = $this->getUser()->getEtudiant()->getFormation()->getCursus()->getNom();

            if($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'Today') {
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus, $formation, $this->getNow(),'Tous');
            }elseif($form->isSubmitted() && $form->isValid())
            {
                // le formulaire à été remplit
                $date_choisis = $form->get('Semaine')->getData();
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus, $formation, $date_choisis,'Tous');
            }else{
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus, $formation, $this->getNow(),'Tous');
            }

            dump($liste_cours);
            $liste_edt = $this->listeEdtPourPlanning($liste_cours);
            dump($liste_edt);




            return $this->render('cour/index.html.twig', [
                'liste_cours' => $liste_cours,
                'liste_edt' => $liste_edt,
                'form' => $form->createView()
            ]);
        }

    }

    private function listeEdtPourPlanning($liste_cours):array{
        $liste_edt = array();
        $j = 1;
        $nbPremierCreneaux = -33;
        if(count($liste_cours) != 0){
            $nbPremierCreneaux = $liste_cours[0]['creneau'];
        }

        dump($nbPremierCreneaux);
        $creneau = ($nbPremierCreneaux - $nbPremierCreneaux % 20) + 1;
        for($i = 0; $i < count($liste_cours) ; $i++){

            while($liste_cours[$i]['creneau'] != $creneau){//permet de repérer les cours manquants de la liste de départ et de mettre des créneaux vide dans la liste
                $liste_edt[$j-1][0] = [];
                $liste_edt[$j-1][1] = [];
                $liste_edt[$j-1][2] = [];
                $liste_edt[$j-1][3] = $creneau;
                $creneau++;
                $j++;
            }

            $liste_edt[$j-1][0] = $liste_cours[$i]['enseignant']['personne']['nom'];
            $liste_edt[$j-1][1] = $liste_cours[$i]['ue']['nom'];
            $liste_edt[$j-1][2] = $liste_cours[$i]['salle']['nom'];
            $liste_edt[$j-1][3] = $liste_cours[$i]['creneau'];
            $j++;
            $creneau++;
        }

        while($i < 20){//Permet de mettre des créneaux vides si pas cours en fin de semaine
            $liste_edt[$j-1][0] = [];
            $liste_edt[$j-1][1] = [];
            $liste_edt[$j-1][2] = [];
            $liste_edt[$j-1][3] = $creneau;
            $creneau++;
            $j++;
            $i++;
        }
        return $liste_edt;
    }
}
