<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Cursus;
use App\Entity\Etudiant;
use App\Entity\Formation;
use App\Entity\Enseignant;
use App\Entity\Personne;
use App\Entity\UE;
use App\Form\cour\CourFilterType;
use App\Form\cour\CourEtuDateFilterType;
use App\Form\cour\EdtEnsRespFilterType;
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
        $etu = $this->isGranted('ROLE_ETUDIANT');
        $ens = $this->isGranted('ROLE_ENSEIGNANT');
        $ens_res = $this->isGranted('ROLE_ENSEIGNANT_RES');//Enseignant res est aussi un prof. Ordre pour les if etu, ens_res, ens

        if($etu or $ens){

            if($ens_res){

                $liste_uetmp = $entityManagerInterface->getRepository(UE::class)->findByFormation($this->getUser()->getEnseignant()->getResponsableFormation());
                $liste_enstmp = $entityManagerInterface->getRepository(Enseignant::class)->sortByNameAscOrDesc('ASC');
                $liste_ens['Tous'] = 'Tous';
                $liste_ue['Tous'] = 'Tous';
                for($i = 0; $i < count($liste_enstmp); $i++)
                {
                    $nom = $liste_enstmp[$i]->getPersonne()->getNom() . ' ' . $liste_enstmp[$i]->getPersonne()->getPrenom();
                    $liste_ens[$nom] = $liste_enstmp[$i]->getPersonne()->getNom();
                }

                for($i = 0; $i < count($liste_uetmp); $i++)
                {
                    $nom = $liste_uetmp[$i]['nom'];
                    $liste_ue[$nom] = $liste_uetmp[$i]['nom'];
                }

                //todo rajouter nom des prof dans l'edt
                $form = $this->createForm(EdtEnsRespFilterType::class, null,[
                    'ue' => $liste_ue,
                    'enseignant' => $liste_ens
                ]);
                $form->add('Valider', SubmitType::class);
                $form->add("Date_du_jour", SubmitType::class, ['label' => "Aujourd'hui"]);


            }else{

                $form = $this->createForm(CourEtuDateFilterType::class);
                $form->add('Valider', SubmitType::class);
                $form->add("Date_du_jour", SubmitType::class, ['label' => "Aujourd'hui"]);
            }

            $form->handleRequest($request);

            if($etu){

                $formation = $this->getUser()->getEtudiant()->getFormation()->getNom();
                $cursus = $this->getUser()->getEtudiant()->getFormation()->getCursus()->getNom();

            }

            if($form->isSubmitted() && $form->isValid() && $form->getClickedButton()->getName() == 'Date_du_jour') {

                if ($etu) {
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneau($cursus, $formation, $this->getNow());

                }elseif($ens_res){

                    $ue_choisis = $form->get('Ue')->getData();
                    $prof_choisis = $form->get('Enseignant')->getData();
                    $formation =$this->getUser()->getEnseignant()->getResponsableFormation()->getnom();
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauProfResp($ue_choisis, $prof_choisis, $formation, $this->getNow());

                }else{
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauByProf($this->getNow(), $this->getUser()->getEnseignant());
                }

            }elseif($form->isSubmitted() && $form->isValid())
            {
                $date_choisis = $form->get('Semaine')->getData();

                if($etu) {
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneau($cursus, $formation, $date_choisis);

                }elseif($ens_res){

                $ue_choisis = $form->get('Ue')->getData();
                $prof_choisis = $form->get('Enseignant')->getData();
                $formation =$this->getUser()->getEnseignant()->getResponsableFormation()->getNom();
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauProfResp($ue_choisis, $prof_choisis, $formation, $date_choisis);

                }else{
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauByProf($date_choisis, $this->getUser()->getEnseignant());

                }
            }else{
                //La premiere fois que la page est chargée
                if($etu) {
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneau($cursus, $formation, $this->getNow());

                }elseif($ens_res) {

                    $formation = $this->getUser()->getEnseignant()->getResponsableFormation()->getNom();
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauProfResp('Tous', $this->getUser()->getEnseignant()->getPersonne()->getNom(), $formation, $this->getNow());

                }else{
                    $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoicesCreneauByProf($this->getNow(), $this->getUser()->getEnseignant());
                }
            }
            $liste_edt = $this->listeEdtPourPlanning($liste_cours);

            return $this->render('cour/index.html.twig', [
                'liste_cours' => $liste_cours,
                'liste_edt' => $liste_edt,
                'form' => $form->createView()
            ]);

        }
        else{
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
            $form->add('Valider', SubmitType::class);
            $form->add("Date_du_jour", SubmitType::class, ['label' => "Aujourd'hui"]);

            $form->handleRequest($request);
            $liste_cours = array();

            if($form->isSubmitted() && $form->isValid())
            {
                // le formulaire à été remplit
                $date_choisis = $form->get('Semaine')->getData();
                $cursus_choisis = $form->get('Cursus')->getData();
                $formation_choisis = $form->get('Formation')->getData();
                $prof_choisis = $form->get('Enseignant')->getData();
                $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus_choisis, $formation_choisis, $date_choisis, $prof_choisis);
            }

            return $this->render('cour/index.html.twig', [
                'liste_cours' => $liste_cours,
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

        $creneau = ($nbPremierCreneaux - $nbPremierCreneaux % 20) + 1;
        for($i = 0; $i < count($liste_cours) ; $i++){

            while($liste_cours[$i]['creneau'] != $creneau){//permet de repérer les cours manquants de la liste de départ et de mettre des créneaux vide dans la liste
                $liste_edt[$j-1][0] = [];
                $liste_edt[$j-1][1] = [];
                $liste_edt[$j-1][2] = [];
                $liste_edt[$j-1][3] = $creneau;
                $liste_edt[$j-1][4] = [];
                $creneau++;
                $j++;
            }

            $liste_edt[$j-1][0] = $liste_cours[$i]['enseignant']['personne']['nom'];
            $liste_edt[$j-1][1] = $liste_cours[$i]['ue']['nom'];
            $liste_edt[$j-1][2] = $liste_cours[$i]['salle']['nom'];
            $liste_edt[$j-1][3] = $liste_cours[$i]['creneau'];
            $liste_edt[$j-1][4] = $liste_cours[$i]['ue']['formation']['nom'];
            $j++;
            $creneau++;
        }

        while($i < 20 and $j-1 != 20 ){//Permet de mettre des créneaux vides si pas cours en fin de semaine
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
