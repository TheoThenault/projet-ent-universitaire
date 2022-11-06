<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/specialite', name: 'specialite_')]
class SpecialiteController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('specialite/index.html.twig', [
            'controller_name' => 'SpecialiteController',
        ]);
    }
}
