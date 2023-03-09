<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCourController extends AbstractController
{
    #[Route('/add/cour', name: 'app_add_cour')]
    public function index(): Response
    {
        return $this->render('add_cour/index.html.twig', [
            'controller_name' => 'AddCourController',
        ]);
    }
}
