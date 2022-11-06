<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation', name: 'formation_')]
class FormationController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig');
    }
}
