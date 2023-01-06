<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Cursus;
use App\Entity\Formation;
use App\Entity\Enseignant;
use App\Form\cour\CourFilterType;
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
        $liste_cur = $entityManagerInterface->getRepository(Cursus::class)->findAllNom();
        $liste_for = $entityManagerInterface->getRepository(Formation::class)->findAllNameOrdered();
        $liste_enstmp = $entityManagerInterface->getRepository(Enseignant::class)->sortByNameAscOrDesc('ASC');
        $liste_ens['Tous'] = 'Tous';
        for($i = 0; $i < count($liste_enstmp); $i++)
        {
            $nom = $liste_enstmp[$i]->getPersonne()->getNom() . ' ' . $liste_enstmp[$i]->getPersonne()->getPrenom();
            $liste_ens[$nom] = $liste_enstmp[$i]->getPersonne()->getNom();
        }

        dump($liste_ens);

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
            'courFormulaire' => $form->createView()
        ]);
    }
}
