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
            switch($responses["email_asc_or_desc"]) {
                case "asc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByEmailAscOrDesc("ASC");
                    break;
                case "desc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByEmailAscOrDesc("DESC");
                    break;
                default:
                    break;
            }
            switch($responses["nom_asc_or_desc"]) {
                case "asc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("ASC");
                    break;
                case "desc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("DESC");
                    break;
                default:
                    break;
            }
            switch($responses["prenom_asc_or_desc"]) {
                case "asc":
                    $enseignants = $entityManager->getRepository(Enseignant::class)->sortByNameAscOrDesc("ASC");
                    break;
                case "desc":
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
