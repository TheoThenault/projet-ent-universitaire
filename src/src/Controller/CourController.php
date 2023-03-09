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
        if($this->getUser()->getEtudiant() == null and $this->getUser()->getEnseignant() == null)
        {
            return $this->render('cour/index.html.twig', ['pasEtudiant' => 42]);
        }

        $etu = $this->isGranted('ROLE_ETUDIANT');
        $ens = $this->isGranted('ROLE_ENSEIGNANT');

        $ens_res = false;
        if($ens) {
            $formation_ref = $this->getUser()->getEnseignant()->getResponsableFormation();
            if(!is_null($formation_ref) && $this->isGranted('ROLE_ENSEIGNANT_RES'))
            {
                $ens_res = true;
            }
        }

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
            //dump($liste_edt);

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
        $nbPremierCreneaux = -33;
        if(count($liste_cours) != 0){
            $nbPremierCreneaux = $liste_cours[0]['creneau'];
        }

        // trouver le premier créneau de la semaine
        $creneau = ($nbPremierCreneaux - $nbPremierCreneaux % 20) + 1;

        // index cours est un curseur sur la liste_cours
        $indexCours = 0;
        // index edt est un curseur sur la liste_edt
        for($indexEDT = 0; $indexEDT < 20; $indexEDT++)
        {
            // si on n'a pas encore parcourue list_cours et que les creneaux correpondent
            if($indexCours < count($liste_cours) && $liste_cours[$indexCours]['creneau'] == $creneau)
            {
                $liste_edt[$indexEDT][0] = $liste_cours[$indexCours]['enseignant']['personne']['nom'];
                $liste_edt[$indexEDT][1] = $liste_cours[$indexCours]['ue']['nom'];
                $liste_edt[$indexEDT][2] = $liste_cours[$indexCours]['salle']['nom'];
                $liste_edt[$indexEDT][3] = $liste_cours[$indexCours]['creneau'];
                $liste_edt[$indexEDT][4] = $liste_cours[$indexCours]['ue']['formation']['nom'];

                // Au cas où deux cours ont le même créneaux (ne dervrait pas arriver)
                /** @noinspection PhpConditionAlreadyCheckedInspection */
                /** PHPStorm dit qu'il y a pas besoin de la boucle en dessous, mais PHPStorm est con */
                while($indexCours < count($liste_cours) && $liste_cours[$indexCours]['creneau'] == $creneau)
                    $indexCours++;
            }else{
                // Si aucun cours
                $liste_edt[$indexEDT][0] = [];
                $liste_edt[$indexEDT][1] = [];
                $liste_edt[$indexEDT][2] = [];
                $liste_edt[$indexEDT][3] = $creneau;
                $liste_edt[$indexEDT][4] = [];
            }
            $creneau++;
        }

        return $liste_edt;
    }

}