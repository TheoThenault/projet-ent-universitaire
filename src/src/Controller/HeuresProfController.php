<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/heuresProf', name: 'heuresProf_')]
class HeuresProfController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('heures_prof/index.html.twig');
    }
}
