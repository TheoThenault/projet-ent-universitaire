<?php

namespace App\Controller;

use App\Entity\Salle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salle', name: 'salle_')]
class SalleController extends AbstractController{
    #[Route('')]
    public function indexAction(): Response{
        return $this->render('salle/index.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    #[Route('/list/{page}', name : 'list', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function listAction(int $page, EntityManagerInterface $em): Response{

        if($page < 1) {
            throw $this->createNotFoundException("La page $page n'existe pas");
        }

        //$nbPerPage = $this->getParameter('my_parameter');//Si on utilise une variable globale pour le nombre par page comme dans le tp symfony
        $nbPerPage = 5;

        $salles = $em->getRepository(Salle::class)
            ->myFindAllByNameWithPaging($page, $nbPerPage);

        $nbTotalPages = intval(ceil(count($salles) / $nbPerPage));

        if($page > $nbTotalPages){
            throw $this->createNotFoundException("La page $page n'existe pas");
        }

        return $this->render('salle/list.html.twig',['salles'=> $salles, 'nbTotalPages'=>$nbTotalPages, 'page'=>$page]);
    }


}