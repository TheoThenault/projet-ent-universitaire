<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Enseignant;
use App\Entity\Groupe;
use App\Entity\Salle;
use App\Entity\UE;
use App\Form\cour\AddCourType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AddCourController extends AbstractController
{

    #[Route('/courAdd', name: 'cour_add')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $salleRepo = $entityManagerInterface->getRepository(Salle::class);
        $profRepo = $entityManagerInterface->getRepository(Enseignant::class);
        $ueRepo = $entityManagerInterface->getRepository(UE::class);
        $courRepo = $entityManagerInterface->getRepository(Cour::class);
        $groupesRepo = $entityManagerInterface->getRepository(Groupe::class);

        $formation = $this->getUser()->getEnseignant()->getResponsableFormation();
        $formation_nom = $formation->getNom() . ' ' . $formation->getCursus()->getNom();

        $ues = $ueRepo->findAllByFormation($formation->getId());
        $list_ues = array();
        for($i = 0; $i < count($ues); $i++)
        {
            $list_ues[$ues[$i]->getNom()] = $ues[$i]->getId();
        }

        $profs = $profRepo->sortByNameAscOrDesc('ASC');
        $list_profs = array();
        for($i = 0; $i < count($profs); $i++)
        {
            $index = $profs[$i]->getPersonne()->getNom() . ' ' . $profs[$i]->getPersonne()->getPrenom();
            $list_profs[$index] = $profs[$i]->getId();
        }

        $salles = $salleRepo->findAll();
        $list_salles = array();
        for($i = 0; $i < count($salles); $i++)
        {
            $index = $salles[$i]->getNom();
            $list_salles[$index] = $salles[$i]->getId();
        }

        $groupes = $groupesRepo->findAllWithSameFormationAndType($formation->getId(), 'Tous');
        $list_grps = array();
        for($i = 0; $i < count($groupes); $i++)
        {
            $index = $groupes[$i]->getType() . ' ' . $groupes[$i]->getId();
            $list_grps[$index] = $groupes[$i]->getId();
        }

        $form = $this->createForm(AddCourType::class, null, ['ues' => $list_ues, 'profs' => $list_profs, 'salles' => $list_salles, 'grps' => $list_grps]);
        $form->add('send', SubmitType::class, ['label' => 'Ajouter un nouveau cour']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $date = $form->get('CRENEAU')->getData();
            $heure = $form->get('HEURE')->getData();
            $ue_id = $form->get('UE')->getData();
            $prof_id = $form->get('ENSEIGNANT')->getData();
            $salle_id = $form->get('SALLE')->getData();
            $groupe_id = $form->get('GROUPE')->getData();

            $datediff = $date->diff($courRepo->getDebutAnnee());
            $numeroSemaine = floor($datediff->days / 7);
            $creneauMin = 20 * $numeroSemaine + 1;  // 20 creneaux par semaines
            $jour_de_la_semaine = $datediff->days % 7;
            $creneau = $creneauMin + ($jour_de_la_semaine * 4) + $heure;

            if($jour_de_la_semaine > 4)
            {
                $this->addFlash('crud', 'Seulement les jours du lundi au vendredi sont autorisés.');
                return $this->render('add_cour/index.html.twig', [
                    'formulaire' => $form->createView(),
                    'formation_nom' =>  $formation_nom
                ]);
            }

            $profREF = $profRepo->findOneBy(['id' => $prof_id]);
            $ueREF = $ueRepo->findOneBy(['id' => $ue_id]);
            $salleREF = $salleRepo->findOneBy(['id' => $salle_id]);
            $groupeREF = $groupesRepo->findOneBy(['id'=> $groupe_id]);

            $cour = new Cour();

            $cour_est_valid = true;

            try{
                $cour->setCreneau($creneau);
                $cour->setEnseignant($profREF);
                $cour->setSalle($salleREF);
                $cour->setUe($ueREF);
                $cour->setGroupe($groupeREF);
                $entityManagerInterface->persist($cour);
                $entityManagerInterface->flush();
            }catch(Exception $e)
            {
                $cour_est_valid = false;
                $erreur = $e->getPrevious()->getPrevious()->errorInfo[2];
                $this->addFlash('crud', $erreur);
                return $this->render('add_cour/index.html.twig', [
                    'formulaire' => $form->createView(),
                    'formation_nom' =>  $formation_nom
                ]);
            };


            if($cour_est_valid)
            {
                return $this->redirectToRoute('cour_index');
            }

//            dump($numeroSemaine, $creneauMin);
//            dump($jour_de_la_semaine, $heure);
//            dump($creneau);
        }

        return $this->render('add_cour/index.html.twig', [
            'formulaire' => $form->createView(),
            'formation_nom' =>  $formation_nom
        ]);
    }
}
