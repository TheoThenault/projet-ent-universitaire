<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Enseignant;
use App\Entity\Groupe;
use App\Entity\Salle;
use App\Entity\UE;
use App\Form\cour\AddCourType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class profileController extends AbstractController
{
    #[Route('/profile', name: 'profile_index')]
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // usually you'll want to make sure the user is authenticated first,
        // see "Authorization" below
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $role = $user->getRoles()[0];
        $role_sans_prefixe = str_replace("ROLE_", "", $role);
        return $this->render('profile/index.html.twig', [
            'nom' => $user->getPrenom(),
            'prenom' => $user->getNom(),
            'email' => $user->getEmail(),
            'role' => $role_sans_prefixe
        ]);
    }
}
