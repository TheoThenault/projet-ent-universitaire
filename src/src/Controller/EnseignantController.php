<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\enseignant\EnseignantFilterType;
use App\Form\enseignant\RechercheProfType;
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

        $form = $this->createForm(RechercheProfType::class);
        $form->add('send', SubmitType::class, ['label' => 'Filtrer']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $responses = $form->getData();
            dump($responses);
            if(array_key_exists('Entry', $responses))
            {
                $enseignants =  $entityManager->getRepository(Enseignant::class)->findByNomOrPrenom($responses['Entry']);
                return $this->render('enseignant/list.twig', ['profForm' => $form->createView(), 'list' => $enseignants]);
            }
        }

        return $this->render('enseignant/list.twig', ['profForm' => $form->createView()]);
    }
}
