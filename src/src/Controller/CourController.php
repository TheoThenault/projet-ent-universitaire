<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cour', name: 'cour_')]
class CourController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {

        return $this->render('cour/index.html.twig', [
            'liste_cours' => ['cour 1']
        ]);
    }
}
