<?php

namespace App\Repository;

use App\Entity\Cour;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Cour>
 *
 * @method Cour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cour[]    findAll()
 * @method Cour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourRepository extends ServiceEntityRepository
{
    private string $date_debut_annee = '2022-09-05';
    //private array $date = [2022, 36];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cour::class);
    }

    public function save(Cour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cour $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllInformations(): array
    {
        $queryBuilder = $this->createQueryBuilder('cr');
        $queryBuilder->addSelect('cr');

        $queryBuilder->leftJoin('cr.ue', 'u');
        $queryBuilder->addSelect('u');

        $queryBuilder->leftJoin('u.formations', 'f');
        $queryBuilder->addSelect('f');

        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');

        $queryBuilder->leftJoin('cr.enseignant', 'e');
        $queryBuilder->addSelect('e');

        $queryBuilder->leftJoin('e.personne', 'p');
        $queryBuilder->addSelect('p');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllByChoices($cursus, $formation, $date, $prof): array
    {
        try {
            $debut_annee = $this->getDebutAnnee();
        } catch (Exception $e) {
            return [];
        }

        if($date < $debut_annee)
        {
            return [];
        }


        $datediff = $date->diff($debut_annee);
        $numeroSemaine = floor($datediff->days / 7);
        $creneauMin = 20 * $numeroSemaine + 1;  // 20 creneaux par semaines
        $creneauMax = $creneauMin + 19;
        //dump($creneauMin);
        //dump($creneauMax);


        $queryBuilder = $this->createQueryBuilder('cour');
        $queryBuilder->addSelect('cour');
        $queryBuilder->leftJoin('cour.enseignant', 'ens');
        $queryBuilder->addSelect('ens');
        $queryBuilder->leftJoin('ens.personne', 'pers');
        $queryBuilder->addSelect('pers');
        $queryBuilder->leftJoin('cour.ue', 'ue');
        $queryBuilder->addSelect('ue');
        $queryBuilder->leftJoin('ue.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');

        $queryBuilder->andWhere('cour.creneau BETWEEN :cmin AND :cmax');
        $queryBuilder->setParameter('cmin', $creneauMin);
        $queryBuilder->setParameter('cmax', $creneauMax);

        if($cursus != 'Tous')
        {
            $queryBuilder->andWhere('c.nom = :cur');
            $queryBuilder->setParameter('cur', $cursus);
        }
        if($formation != 'Tous') {
            $queryBuilder->andWhere('f.nom = :for');
            $queryBuilder->setParameter('for', $formation);
        }
        if($prof != 'Tous') {
            $queryBuilder->andWhere('pers.nom = :prof');
            $queryBuilder->setParameter('prof', $prof);
        }

        $queryBuilder->orderBy('cour.creneau');
        $result = $queryBuilder->getQuery()->getArrayResult();

        //dump(count($result));
        for($i = 0; $i < count($result); $i++)
        {
            $curr = $result[$i]['creneau'] - 1;
            //dump($result[$i]);
            $res = $this->creneauToDate($curr);
            if($res != null)
                $result[$i]['creneau'] = $res;
        }

        return $result;
    }

    public function findAllByProf($profPersID): array
    {
        $queryBuilder = $this->createQueryBuilder('cour');
        $queryBuilder->addSelect('cour');
        $queryBuilder->leftJoin('cour.enseignant', 'ens');
        $queryBuilder->addSelect('ens');
        $queryBuilder->leftJoin('ens.personne', 'pers');
        $queryBuilder->addSelect('pers');
        $queryBuilder->leftJoin('cour.ue', 'ue');
        $queryBuilder->addSelect('ue');
        $queryBuilder->leftJoin('cour.groupe', 'grp');
        $queryBuilder->addSelect('grp');
        $queryBuilder->leftJoin('ue.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');

        $queryBuilder->andWhere('pers.id = :id');
        $queryBuilder->setParameter('id', $profPersID);

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllByChoicesCreneau($cursus, $formation, $date): array
    {
        try {
            $debut_annee = $this->getDebutAnnee();
        } catch (Exception $e) {
            return [];
        }

        $datediff = $date->diff($debut_annee);
        if($datediff->days < 0)
        {
            return [];
        }
        $numeroSemaine = floor($datediff->days / 7);
        $creneauMin = 20 * $numeroSemaine + 1;  // 20 creneaux par semaines
        $creneauMax = $creneauMin + 19;
        //dump($creneauMin);
        //dump($creneauMax);


        $queryBuilder = $this->createQueryBuilder('cour');
        $queryBuilder->addSelect('cour');

        $queryBuilder->leftJoin('cour.salle', 's');
        $queryBuilder->addSelect('s');
        $queryBuilder->leftJoin('cour.enseignant', 'ens');
        $queryBuilder->addSelect('ens');
        $queryBuilder->leftJoin('ens.personne', 'pers');
        $queryBuilder->addSelect('pers');
        $queryBuilder->leftJoin('cour.ue', 'ue');
        $queryBuilder->addSelect('ue');
        $queryBuilder->leftJoin('ue.formation', 'f');
        $queryBuilder->addSelect('f');
        $queryBuilder->leftJoin('f.cursus', 'c');
        $queryBuilder->addSelect('c');

        //$queryBuilder->addSelect('cour.creneau');

        $queryBuilder->andWhere('cour.creneau BETWEEN :cmin AND :cmax');
        $queryBuilder->setParameter('cmin', $creneauMin);
        $queryBuilder->setParameter('cmax', $creneauMax);

        if($cursus != 'Tous')
        {
            $queryBuilder->andWhere('c.nom = :cur');
            $queryBuilder->setParameter('cur', $cursus);
        }
        if($formation != 'Tous') {
            $queryBuilder->andWhere('f.nom = :for');
            $queryBuilder->setParameter('for', $formation);
        }

        $queryBuilder->orderBy('cour.creneau');
        $result = $queryBuilder->getQuery()->getArrayResult();

        //dump(count($result));
        /*for($i = 0; $i < count($result); $i++)
        {
            $curr = $result[$i]['creneau'] - 1;
            //dump($result[$i]);
            $res = $this->creneauToDate($curr);
            if($res != null)
                $result[$i]['creneau'] = $res;
        }*/

        return $result;
    }

    /**
     * @throws Exception
     */
    private function getDebutAnnee() : DateTime
    {
        return new DateTime($this->date_debut_annee, null);
    }

    private function creneauToDate($creneau) : ?string
    {
        $nWeek    = floor($creneau / 20);  // 20 creneaux par semaines
        $weekTime = $creneau % 20;              // creneau dans la semaine
        $weekDay  = floor($weekTime / 4);  // 4 creneaux par jour
        $dayTime  = $weekTime - (4*$weekDay);
        $dayTime  = 8 + $dayTime*2 + ($dayTime>1?2:0);

        //dump('c '.$creneau.' w '.$nWeek.' wt '.$weekTime.' wd '.$weekDay.' dt '.$dayTime);

        $interval = null;
        try {
            $interval = new DateInterval('P' . $nWeek . 'W' . $weekDay . 'DT' . $dayTime . 'H');
        } catch (Exception $e) {
        }
        if($interval == null)
            return null;

        try {
            $result = $this->getDebutAnnee();
        } catch (Exception $e) {
            return null;
        }

        $result->add($interval);
        return $result->format('H:i d-m-o');
    }

//    /**
//     * @return Cour[] Returns an array of Cour objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cour
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
