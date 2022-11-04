<?php

namespace App\Controller;

use App\Form\CursusFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/cursus', name: 'cursus_')]
class CursusController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CursusFilterType::class);
        $form->add('Filtrer', SubmitType::class);
        $form->handleRequest($request);

        return $this->render('cursus/index.html.twig', [
            'cursusFormulaire' => $form->createView(),
        ]);
    }
}
