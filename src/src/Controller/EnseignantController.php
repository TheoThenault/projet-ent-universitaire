<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Entity\Formation;
use App\Entity\Personne;
use App\Entity\Specialite;
use App\Entity\StatutEnseignant;
use App\Form\enseignant\EnseignantEditType;
use App\Form\enseignant\EnseignantFilterType;
use App\Form\enseignant\EnseignantType;
use App\Form\enseignant\RechercheProfType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
        $form->add('send', SubmitType::class, ['label' => 'Filtrer',
                'attr' => [ 'formaction' => '/enseignant/list' ]
            ]);
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

    #[Route('/add', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request,  ValidatorInterface $validator): Response
    {
        // Get all the Entity in the repos.
        $listeStatus = $entityManager->getRepository(StatutEnseignant::class)->getAllForm();
        $listeSpecialites = $entityManager->getRepository(Specialite::class)->getAllForm();
        $listeFormations = $entityManager->getRepository(Formation::class)->getAllForm();

        $form = $this->createForm(EnseignantType::class, null,[
            'status' => $listeStatus,
            'specialites' => $listeSpecialites,
            'formations' => $listeFormations
        ]);
        $form->add('send', SubmitType::class, ['label' => 'Créer']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the personne and enseignant
            $newPersonne = new Personne();
            $newPersonne->setNom($form->get('Nom')->getData());
            $newPersonne->setPrenom($form->get('Prenom')->getData());
            $newPersonne->setEmail($form->get('Prenom')->getData() . '.' . $form->get('Nom')->getData() . '@univ-poitiers.fr');
            $newPersonne->setPassword('');
            $newPersonne->setRoles(['ROLE_ENSEIGNANT']);
            $newEnseignant = new Enseignant();
            $newEnseignant->setPersonne($newPersonne);
            $newEnseignant->setStatutEnseignant($entityManager->getRepository(StatutEnseignant::class)->findOneBy(['id' => $form->get('Status')->getData()]));
            $newEnseignant->setSection($entityManager->getRepository(Specialite::class)->findOneBy(['id' => $form->get('SectionDenseignement')->getData()]));
            $formationres = $form->get('FormationRes')->getData();

            if($formationres != 'non')
            {
                // Prof responsable
                $newEnseignant->setResponsableFormation($formationres);
            }

            // Personne Validation
            $errors = $validator->validate($newPersonne);
            if(count($errors) <= 0){
                // No error :
                $entityManager->persist($newEnseignant);
                $entityManager->flush();

                $this->addFlash('crud', "L'enseignant : {$form->get('Nom')->getData()} {$form->get('Prenom')->getData()}, a été créé avec succès.");
                return $this->redirectToRoute('enseignant_list');
            } else {
                // Error
                return $this->render('enseignant/add.html.twig', [
                    'profForm' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

        }


        return $this->render('enseignant/add.html.twig', [
            'addEnseignantForm' => $form->createView(),
            'errors' => [],
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id, ValidatorInterface $validator): Response
    {
        $enseignant = $entityManager->getRepository(Enseignant::class)->find($id);

        // Get all the Entity in the repos.
        $listeStatus = $entityManager->getRepository(StatutEnseignant::class)->getAllForm();
        $listeSpecialites = $entityManager->getRepository(Specialite::class)->getAllForm();
        $listeFormations = $entityManager->getRepository(Formation::class)->getAllForm();

        $form = $this->createForm(EnseignantType::class, null,[
            'status' => $listeStatus,
            'specialites' => $listeSpecialites,
            'formations' => $listeFormations
        ]);

        // feed the form
        $this->feedEditForm($form, $enseignant);

        $form->add('send', SubmitType::class, ['label' => "Modifier l'enseignant"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personne = $enseignant->getPersonne();

            // Update the personne and enseignant
            $personne->setNom($form->get('Nom')->getData());
            $personne->setPrenom($form->get('Prenom')->getData());
            $enseignant->setStatutEnseignant($entityManager->getRepository(StatutEnseignant::class)->findOneBy(['id' => $form->get('Status')->getData()]));
            $enseignant->setSection($entityManager->getRepository(Specialite::class)->findOneBy(['id' => $form->get('SectionDenseignement')->getData()]));
            $formationres = $form->get('FormationRes')->getData();

            if($formationres != 'non')
            {
                // Prof responsable
                $enseignant->setResponsableFormation($formationres);
            }

            // Personne Validation
            $errors = $validator->validate($personne);
            if(count($errors) <= 0) {
                // No error :
                $entityManager->persist($enseignant);
                $entityManager->flush();

                $this->addFlash('crud', "L'enseignant : {$form->get('Nom')->getData()} {$form->get('Prenom')->getData()}, a été modifié avec succès.");
                return $this->redirectToRoute('enseignant_list');
            } else {
                // Error:
                return $this->render('enseignant/edit.html.twig', [
                    'editEnseignantForm' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

        }

        return $this->render('enseignant/edit.html.twig', [
            'editEnseignantForm' => $form->createView(),
            'errors' => [],
        ]);
    }

    private function feedEditForm($form, $enseignant){
        $form->get('Nom')->setData($enseignant->getPersonne()->getNom());
        $form->get('Prenom')->setData($enseignant->getPersonne()->getPrenom());
        $form->get('Status')->setData($enseignant->getStatutEnseignant()->getNom());
        $form->get('SectionDenseignement')->setData($enseignant->getSection()->getNom());
        $form->get('FormationRes')->setData($enseignant->getResponsableFormation());
    }
}
