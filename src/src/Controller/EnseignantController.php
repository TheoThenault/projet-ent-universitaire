<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\enseignant\EnseignantFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/enseignant', name: 'enseignant_')]
class EnseignantController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $enseignants =  $entityManager->getRepository(Enseignant::class)->findAll();

        $form = $this->createForm(EnseignantFilterType::class);
        $form->add('send', SubmitType::class, ['label' => 'Filter']);

        if ($form->isSubmitted()) {
            dump("ccccccc");
        }


        return $this->render('enseignant/list.twig', [
            'enseignants' => $enseignants,
            'enseignantFilterForm' => $form->createView(),
        ]);
    }
}
