<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\enseignant\EnseignantFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/enseignant', name: 'enseignant_')]
class EnseignantController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $enseignants =  $entityManager->getRepository(Enseignant::class)->findAll();

        $form = $this->createForm(EnseignantFilterType::class);
        $form->add('send', SubmitType::class, ['label' => 'Filter']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $responses = $form->getData();
            dump($responses);
            $choix_statut = $form->get("statut_enseignant")->getData()->getNom();
            $enseignants = $entityManager->getRepository(Enseignant::class)->filterByStatut($choix_statut);

            switch($responses["sort_asc_or_desc"]) {
                case "email":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByEmailAscOrDesc("ASC");
                    break;
                case "email_desc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByEmailAscOrDesc("DESC");
                    break;
                case "nom":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("ASC");
                    break;
                case "nom_desc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("DESC");
                    break;
                case "prenom":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("ASC");
                    break;
                case "prenom_desc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("DESC");
                    break;
                default:
                    break;
            }

            return $this->render('enseignant/list.twig', [
                'enseignants' => $enseignants,
                'enseignantFilterForm' => $form->createView(),
            ]);
        }

        return $this->render('enseignant/list.twig', [
            'enseignants' => $enseignants,
            'enseignantFilterForm' => $form->createView(),
        ]);
    }
}
