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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/enseignant', name: 'enseignant_')]
class EnseignantController extends AbstractController
{
    #[Route('/list/{nPage}', name: 'list',
        requirements:   ['nPage' => '\d+'],
        defaults:       ['nPage' => 1]
    )]
    public function index($nPage, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($nPage <= 0)
        {
            throw new NotFoundHttpException('La page n\'existe pas');
        }

        $form = $this->createForm(RechercheProfType::class);
        $form->add('send', SubmitType::class, ['label' => 'Filtrer']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $responses = $form->getData();
            //dump($responses);
            if(array_key_exists('Entry', $responses))
            {
                $perPage = $this->getParameter('lignes_par_page');

                $enseignants =  $entityManager->getRepository(Enseignant::class)
                    ->findByNomOrPrenomArrayPaged(explode(' ', $responses['Entry']), $nPage, $perPage);

                //dump($enseignants->getQuery()->getResult());
                //dump(count($enseignants));
                $pageMax = intval(ceil(count($enseignants)/$perPage));
                if($nPage != 1 && $nPage > $pageMax)  // Différent de 1 car si la BDD est vide on veut quand même afficher une page basique pour l'utilisateur
                {
                    throw new NotFoundHttpException('La page n\'existe pas');
                }

                return $this->render('enseignant/list.twig', ['profForm' => $form->createView(), 'list' => $enseignants,
                    'currPage' => $nPage, 'pageMax' => $pageMax]);
            }
        }

        return $this->render('enseignant/list.twig', ['profForm' => $form->createView()]);
    }
}
