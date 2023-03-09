<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AddCourController extends AbstractController
{
    #[Route('/courAdd', name: 'cour_add')]
    public function index(): Response
    {
        return $this->render('add_cour/index.html.twig', [
            'formulaire' => null,
        ]);
    }
}
