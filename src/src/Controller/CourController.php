<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Cursus;
use App\Entity\Formation;
use App\Entity\UE;
use App\Form\CourFilterType;
use App\Form\UEFilterType;
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
        $form = $this->createForm(CourFilterType::class, null, [
            'cursus' => $liste_cur,
            'formation' => $liste_for
        ]);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);
        $liste_cours = array();

        if($form->isSubmitted() && $form->isValid())
        {
            // le formulaire à été remplit
            $date_choisis = $form->get('Semaine')->getData();
            dump($date_choisis);
            $cursus_choisis = $form->get('Cursus')->getData();
            $formation_choisis = $form->get('Formation')->getData();
            $liste_cours = $entityManagerInterface->getRepository(Cour::class)->findAllByChoices($cursus_choisis, $formation_choisis, $date_choisis);
        }

        //dump($liste_cours);
        return $this->render('cour/index.html.twig', [
            'liste_cours' => $liste_cours,
            'courFormulaire' => $form->createView()
        ]);
    }
}
