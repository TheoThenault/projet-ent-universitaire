<?php

namespace App\Controller;

use App\Entity\Cour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/heuresProf', name: 'heuresProf_')]
class HeuresProfController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        if($this->getUser()->getEnseignant() == null)
        {
            return $this->render('heures_prof/index.html.twig');
        }

        $profPersID = $this->getUser()->getId();
        //dump($profPersID);

        $listeCours = $entityManagerInterface->getRepository(Cour::class)->findAllByProf($profPersID);
        //dump($listeCours);

        $heures = array();
        $sommeTD = 0;
        $sommeTP = 0;
        $sommeCM = 0;
        foreach ($listeCours as $c)
        {
            $ueID  = $c['ue']['id'];
            $grpT  = $c['groupe']['type'];

            if(!array_key_exists($ueID, $heures))
            {
                $heures[$ueID] = array();
                $heures[$ueID]['nom'] = $c['ue']['nom'];

                $formation = $c['ue']['formation'];
                $heures[$ueID]['Formation'] = $formation['nom'];
                $heures[$ueID]['Cursus']    = $formation['cursus']['nom'];

                $heures[$ueID]['TD'] = 0;
                $heures[$ueID]['TP'] = 0;
                $heures[$ueID]['CM'] = 0;
                $heures[$ueID]['TOTAL'] = 0;
            }

            $heures[$ueID][$grpT] += 2;
            $heures[$ueID]['TOTAL'] +=2;

            switch ($grpT){
                case 'TD' :
                    $sommeTD+=2;
                    break;
                case 'TP' :
                    $sommeTP+=2;
                    break;
                case 'CM' :
                    $sommeCM+=2;
                    break;
            }
        }

        $heures['total']['nom'] = 'Total';
        $heures['total']['Formation'] = '';
        $heures['total']['Cursus'] = '';
        $heures['total']['TD'] = $sommeTD;
        $heures['total']['TP'] = $sommeTP;
        $heures['total']['CM'] = $sommeCM;
        $heures['total']['TOTAL'] = $sommeCM + $sommeTP + $sommeTD;

        $heures['min']['nom'] = 'Heures Minimales';
        $heures['min']['Formation'] = '';
        $heures['min']['Cursus'] = '';
        $heures['min']['TD'] = '';
        $heures['min']['TP'] = '';
        $heures['min']['CM'] = '';
        $heures['min']['TOTAL'] = $this->getUser()->getEnseignant()->getStatutEnseignant()->getNbHeureMin();

        $heures['sup']['nom'] = 'Heures supplémentaires';
        $heures['sup']['Formation'] = '';
        $heures['sup']['Cursus'] = '';
        $heures['sup']['TD'] = '';
        $heures['sup']['TP'] = '';
        $heures['sup']['CM'] = '';
        $hSup = ($sommeCM + $sommeTP + $sommeTD) - $this->getUser()->getEnseignant()->getStatutEnseignant()->getNbHeureMax();
        $heures['sup']['TOTAL'] = $hSup > 0 ? $hSup : 0;

        //dump($heures);

        return $this->render('heures_prof/index.html.twig', ['list_UES' => $heures]);
    }
}
